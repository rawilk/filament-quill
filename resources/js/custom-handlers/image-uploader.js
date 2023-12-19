import LoadingImage from '../blots/image';

class ImageUploader {
    constructor(quill, options) {
        this.quill = quill;
        this.options = options;
        this.range = null;
        this.placeholderDelta = null;
        this.allowImages = options.allowImages;

        if (typeof this.options.upload !== 'function') {
            console.warn('[Missing config] upload function that returns a promise is required');
        }

        let toolbar = this.quill.getModule('toolbar');
        if (toolbar) {
            toolbar.addHandler('image', this.selectLocalImage.bind(this));
        }

        this.handleDrop = this.handleDrop.bind(this);
        this.handlePaste = this.handlePaste.bind(this);

        this.quill.root.addEventListener('drop', this.handleDrop, false);
        this.quill.root.addEventListener('paste', this.handlePaste, false);
    }

    selectLocalImage() {
        if (! this.allowImages) {
            return;
        }

        this.quill.focus();
        this.range = this.quill.getSelection();

        this.fileHolder = document.createElement('input');
        this.fileHolder.setAttribute('type', 'file');
        this.fileHolder.setAttribute('accept', 'image/*');
        this.fileHolder.setAttribute('style', 'visibility:hidden');
        this.fileHolder.onchange = this.fileChanged.bind(this);

        document.body.appendChild(this.fileHolder);

        this.fileHolder.click();

        window.requestAnimationFrame(() => {
            document.body.removeChild(this.fileHolder);
        });
    }

    handleDrop(event) {
        if (
            event.dataTransfer &&
            event.dataTransfer.files &&
            event.dataTransfer.files.length
        ) {
            event.stopPropagation();
            event.preventDefault();

            if (! this.allowImages) {
                return;
            }

            if (document.caretRangeFromPoint) {
                const selection = document.getSelection();
                const range = document.caretRangeFromPoint(event.clientX, event.clientY);

                if (selection && range) {
                    selection.setBaseAndExtent(
                        range.startContainer,
                        range.startOffset,
                        range.startContainer,
                        range.startOffset
                    );
                }
            } else {
                const selection = document.getSelection();
                const range = document.caretPositionFromPoint(event.clientX, event.clientY);

                if (selection && range) {
                    selection.setBaseAndExtent(
                        range.offsetNode,
                        range.offset,
                        range.offsetNode,
                        range.offset
                    );
                }
            }

            this.quill.focus();
            this.range = this.quill.getSelection();

            const file = event.dataTransfer.files[0];

            setTimeout(() => {
                this.quill.focus();
                this.range = this.quill.getSelection();

                this.readAndUploadFile(file);
            }, 0);
        }
    }

    handlePaste(event) {
        let clipboard = event.clipboardData || window.clipboardData;

        // IE 11 is `.files`, others are `.items`.
        if (clipboard && (clipboard.items || clipboard.files)) {
            let items = clipboard.items || clipboard.files;
            const IMAGE_MIME_REGEX = /^image\/(jpe?g|gif|png|svg|webp)$/i;

            for (let i = 0; i < items.length; i++) {
                if (this.allowImages && IMAGE_MIME_REGEX.test(items[i].type)) {
                    let file = items[i].getAsFile ? items[i].getAsFile() : items[i];

                    if (file) {
                        this.quill.focus();
                        this.range = this.quill.getSelection();

                        event.preventDefault();

                        setTimeout(() => {
                            this.quill.focus();
                            this.range = this.quill.getSelection();

                            this.readAndUploadFile(file);
                        }, 0);
                    }
                }
            }
        }
    }

    readAndUploadFile(file) {
        let isUploadRejected = false;

        const fileReader = new FileReader();

        fileReader.addEventListener(
            'load',
            () => {
                if (! isUploadRejected) {
                    this.insertBase64Image(fileReader.result);
                }
            },
            false
        );

        if (file) {
            fileReader.readAsDataURL(file);
        }

        this.options.upload(file).then(
            imageUrl => {
                this.insertToEditor(imageUrl);
            },
            error => {
                isUploadRejected = true;
                this.removeBase64Image();

                console.warn(error);
            }
        );
    }

    fileChanged() {
        const file = this.fileHolder.files[0];

        this.readAndUploadFile(file);
    }

    insertBase64Image(url) {
        const range = this.range;

        this.placeholderDelta = this.quill.insertEmbed(
            range.index,
            LoadingImage.blotName,
            url,
            'user',
        );
    }

    insertToEditor(url) {
        const range = this.range;

        const lengthToDelete = this.calculatePlaceholderInsertLength();

        // Delete the placeholder image
        this.quill.deleteText(range.index, lengthToDelete, 'api');

        // Insert the server saved image
        this.quill.insertEmbed(range.index, 'image', url, 'user');

        range.index++;

        this.quill.setSelection(range, 'user');
    }

    calculatePlaceholderInsertLength() {
        return this.placeholderDelta.ops.reduce((accumulator, deltaOperation) => {
            if (deltaOperation.hasOwnProperty('insert'))
                accumulator++;

            return accumulator;
        }, 0);
    }

    removeBase64Image(source = 'user') {
        const range = this.range;
        const lengthToDelete = this.calculatePlaceholderInsertLength();

        this.quill.deleteText(range.index, lengthToDelete ?? 1, source);
    }
}

window.QuillImageUploader = ImageUploader;

export default ImageUploader;
