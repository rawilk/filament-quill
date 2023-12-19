import Quill from 'quill';
import {
    mapFontName,
    mapFontToStyle,
    mapFontSizeToStyle,
    getStyle,
    getLabel,
    isFunction,
    getImageUrls,
} from './utils.js';
import ImageUploader from './custom-handlers/image-uploader';

Quill.register('modules/imageUploader', ImageUploader);

window.Quill = Quill;

export default function quill({
    state,
    statePath,
    placeholder = null,
    handlers = {},
    options = {},
    wireId = undefined,
    allowImages = true,
    hasHistory = false,
    onTextChangedHandler = undefined,
    onInit = undefined,
}) {
    return {
        state,
        statePath,
        placeholder,
        options,
        handlers,
        wireId,
        allowImages,
        hasHistory,
        onTextChangedHandler,
        onInit,
        editorInstance: undefined,
        labelClickHandler: undefined,

        init() {
            // Setting a small timeout so the icons aren't flashing as huge elements
            // before the styles are loaded.
            const timeout = window.__quillStylesLoaded ? 0 : 25;

            setTimeout(() => {
                this.initEditor(state.initialValue);
            }, timeout);

            this.$watch('state', () => {
                if (this.$refs.quill.contains(document.activeElement)) {
                    return;
                }

                this.setEditorValue(this.state, 'silent');
            });

            this.labelClickHandler = () => this.focus();

            getLabel(this.statePath)?.addEventListener('click', this.labelClickHandler);
        },

        destroy() {
            getStyle(this.statePath)?.remove();
            getLabel(this.statePath)?.removeEventListener('click', this.labelClickHandler);

            this.editorInstance = this.$root._editor = undefined;
        },

        initEditor(content) {
            let _this = this;

            const fontStyles = _this.loadFonts();
            const sizeStyles = _this.loadFontSizes();

            _this.addStylesToDom(fontStyles + sizeStyles);

            this.$root._editor = _this.editorInstance = new Quill(_this.$refs.quill, {
                theme: _this.options.theme,
                placeholder: _this.placeholder,
                modules: _this.getModules(),
            });

            _this.editorInstance.on('text-change', (delta, oldDelta, source) => {
                if (isFunction(_this.onTextChangedHandler)) {
                    const result = _this.onTextChangedHandler(delta, oldDelta, source, _this);

                    if (result === false) {
                        return;
                    }
                }

                if (allowImages) {
                    _this.checkForImageDifferences(oldDelta, source);
                }

                _this.state = _this.editorInstance.root.innerHTML;
            });

            const el = _this.$refs.quill.querySelector('.ql-editor');
            ['prose', 'max-w-none', 'dark:prose-invert'].forEach(className => el?.classList.add(className));

            _this.setEditorValue(content, 'silent');

            if (_this.options.autofocus) {
                queueMicrotask(() => _this.focus());
            }

            if (isFunction(_this.onInit)) {
                _this.onInit(_this.editor(), _this);
            }
        },

        wire() {
            return window.Livewire.find(this.wireId);
        },

        editor() {
            return Alpine.raw(this.editorInstance);
        },

        focus() {
            this.editor()?.focus();
        },

        setEditorValue(value, source = 'user') {
            const normalizedValue = this.editor().clipboard.convert(value);

            this.editor().setContents(normalizedValue, source);
        },

        loadFonts() {
            if (! options.fonts) {
                return '';
            }

            const fonts = Quill.import('attributors/class/font');

            fonts.whitelist = options.fonts.map(font => mapFontName(font));
            Quill.register(fonts, true);

            return options.fonts.map(font => mapFontToStyle(font, this.statePath)).join('');
        },

        loadFontSizes() {
            if (! options.fontSizes) {
                return '';
            }

            const sizes = Quill.import('attributors/style/size');
            sizes.whitelist = options.fontSizes;

            Quill.register(sizes, true);

            return options.fontSizes.map(size => mapFontSizeToStyle(size, this.statePath)).join('');
        },

        addStylesToDom(styles) {
            if (! styles) {
                return;
            }

            const el = document.createElement('style');
            el.id = `quill--${this.statePath.replace('.', '-')}`;
            el.innerHTML = styles;

            document.head.appendChild(el);
        },

        getModules() {
            let _this = this;

            const toolbarHandlers = {};
            if (_this.hasHistory) {
                toolbarHandlers.undo = function () {
                    this.quill.history.undo();
                };

                toolbarHandlers.redo = function () {
                    this.quill.history.redo();
                };
            }

            const modules = {
                toolbar: {
                    container: _this.$refs.toolbar,
                    handlers: {
                        ...toolbarHandlers,
                        ...handlers,
                    },
                },

                imageUploader: {
                    allowImages,
                    upload: file => {
                        return new Promise((resolve) => {
                            _this.wire()?.upload(
                                `componentFileAttachments.${_this.statePath}`,
                                file,
                                () => {
                                    _this.wire()
                                        .getFormComponentFileAttachmentUrl(`${_this.statePath}`)
                                        .then(url => {
                                            this.$dispatch('quill-image-uploaded', { url, statePath: _this.statePath });

                                            resolve(url);
                                        });
                                }
                            );
                        });
                    }
                },
            };

            if (_this.hasHistory) {
                modules.history = {
                    delay: 1000,
                    maxStack: 100,
                    userOnly: false,
                };
            }

            return modules;
        },

        clearHistory({ detail: { id } }) {
            if (id !== this.statePath) {
                return;
            }

            if (! this.hasHistory) {
                return;
            }

            this.editor().history.clear();
        },

        checkForImageDifferences(oldDelta, source) {
            if (source !== 'user') {
                return;
            }

            const deletedImageUrls = getImageUrls(this.editor().getContents().diff(oldDelta));

            if (deletedImageUrls.length) {
                this.$dispatch('quill-images-deleted', { urls: deletedImageUrls, statePath: this.statePath });
            }
        },
    };
}
