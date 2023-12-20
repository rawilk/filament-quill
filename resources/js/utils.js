export const mapFontName = name => name.toLowerCase().replace(/\s/g, '-');

export const mapFontToStyle = (font, editorId) => {
    const styleName = mapFontName(font);

    return `
    [data-quill-id="${editorId}"] .ql-snow .ql-picker.ql-font .ql-picker-label[data-value="${styleName}"]::before,
    [data-quill-id="${editorId}"] .ql-snow .ql-picker.ql-font .ql-picker-item[data-value="${styleName}"]::before {
        content: '${font}';
    }

    [data-quill-id="${editorId}"] .ql-font-${styleName} {
        font-family: '${font}', sans-serif;
    }
    `;
};

export const isFunction = subject => typeof subject === 'function';

export const mapFontSizeToStyle = (size, editorId) => {
    return `
    [data-quill-id="${editorId}"] .ql-snow .ql-picker.ql-size .ql-picker-item[data-value="${size}"]::before,
    [data-quill-id="${editorId}"] .ql-snow .ql-picker.ql-size .ql-picker-label[data-value="${size}"]::before {
        content: '${size}';
    }
    `;
};

export const getStyle = editorId => document.head.querySelector(`style#quill--${editorId.replace('.', '-')}`);

export const getLabel = editorId => document.querySelector(`label[for="${editorId}"]`);

export const getImageUrls = delta => delta.ops.filter(op => op.insert && op.insert.image).map(op => op.insert.image);

export const stickyObserver = (anchor, el, topbarSelector) => {
    if (! anchor) {
        return;
    }

    const topbar = document.querySelector(topbarSelector);
    const offsetHeight = topbar?.offsetHeight ?? 0;

    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                el?.classList.remove('sticky');
                el?.style.removeProperty('--sticky-offset');
            } else {
                el?.classList.add('sticky');
                el?.style.setProperty('--sticky-offset', `${offsetHeight + 2}px`);
            }
        }, { threshold: .25, rootMargin: `${offsetHeight}px` });
    });

    observer.observe(anchor);

    return observer;
};
