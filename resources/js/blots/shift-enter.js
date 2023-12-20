import Quill from 'quill';

const Parchment = Quill.import('parchment');

class ShiftEnterBlot extends Parchment.Embed {}

ShiftEnterBlot.blotName = 'ShiftEnter';
ShiftEnterBlot.tagName = 'br';

export default ShiftEnterBlot;
