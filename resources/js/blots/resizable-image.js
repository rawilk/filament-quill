import Quill from 'quill';
import BaseImage from 'quill/formats/image.js';

/**
 * Quill 1.x's built-in image blot only whitelists `alt`, `height` and `width`.
 * The blot-formatter module resizes images by writing `width`/`height` attributes
 * (which already round-trip) and aligns them by writing a `data-align` attribute.
 *
 * For the resize/alignment to survive a save + reload, every attribute the module
 * touches must be declared as a format here — otherwise Quill's clipboard converter
 * silently drops it when it rebuilds the editor contents from the saved HTML.
 *
 * Alignment is persisted as the `data-align` attribute only (no inline styles); the
 * float/margin rendering is driven from CSS (see `content.css`) so the saved markup
 * stays clean and the editor view matches the reloaded output.
 */
const ATTRIBUTES = ['alt', 'height', 'width', 'data-align'];

class ResizableImage extends BaseImage {
    static formats(domNode) {
        return ATTRIBUTES.reduce((formats, attribute) => {
            if (domNode.hasAttribute(attribute)) {
                formats[attribute] = domNode.getAttribute(attribute);
            }

            return formats;
        }, {});
    }

    format(name, value) {
        if (ATTRIBUTES.indexOf(name) > -1) {
            if (value) {
                this.domNode.setAttribute(name, value);
            } else {
                this.domNode.removeAttribute(name);
            }
        } else {
            super.format(name, value);
        }
    }
}

// Overwrite Quill's default image format so the extra attributes are allowed.
Quill.register(ResizableImage, true);

export default ResizableImage;
