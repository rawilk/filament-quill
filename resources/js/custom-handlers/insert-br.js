import ShiftEnter from '../blots/shift-enter.js';
import Quill from 'quill';

Quill.register(ShiftEnter);

const Delta = Quill.import('delta');

class InsertBr {
    constructor(quill, options) {
        this.quill = quill;
        this.options = options;

        const binding = {
            key: 'Enter',
            shiftKey: true,
            handler: function (range) {
                this.quill.updateContents(
                    new Delta()
                        .retain(range.index)
                        .delete(range.length)
                        .insert({ ShiftEnter: true }),
                    'user',
                );

                if (! this.quill.getLeaf(range.index + 1)[0].next) {
                    this.quill.updateContents(
                        new Delta()
                            .retain(range.index + 1)
                            .delete(0)
                            .insert({ ShiftEnter: true }),
                        'user',
                    );
                }

                this.quill.setSelection(range.index + 1, 'silent');

                // Don't call other candidate handlers.
                return false;
            },
        };

        this.quill.keyboard.bindings.Enter ??= [];
        this.quill.keyboard.bindings.Enter.unshift(binding);
    }
}

export default InsertBr;
