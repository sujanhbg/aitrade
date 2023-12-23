CKEDITOR.editorConfig = function (config) {
    config.toolbarGroups = [
        {name: 'document', groups: ['Source', '-', 'mode', 'document', 'doctools']},
        {name: 'clipboard', groups: ['clipboard', 'undo']},
        {name: 'editing', groups: ['find', 'selection', 'spellchecker', 'editing']},
        {name: 'forms', groups: ['forms']},
        {name: 'basicstyles', groups: ['basicstyles', 'cleanup']},
        {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi', 'paragraph']},
        {name: 'links', groups: ['links']},
        {name: 'insert', groups: ['insert']},
        {name: 'styles', groups: ['styles']},
        {name: 'colors', groups: ['colors']},
        {name: 'tools', groups: ['tools']},
        {name: 'others', groups: ['others']},
        {name: 'about', groups: ['about']}
    ];

    config.removeButtons = 'Save,Templates,Cut,Find,SelectAll,Scayt,Form,CopyFormatting,NewPage,Preview,Print,Copy,Paste,PasteText,PasteFromWord,Redo,Undo,Replace,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Outdent,Indent,Blockquote,BidiLtr,BidiRtl,Language,Flash,HorizontalRule,PageBreak,Iframe,Font,Maximize,About,Styles';

    config.filebrowserImageBrowseUrl = '../filemanager/dialog.php?type=1&editor=ckeditor&fldr=';
    config.uiColor = '#ffcce6';

};

