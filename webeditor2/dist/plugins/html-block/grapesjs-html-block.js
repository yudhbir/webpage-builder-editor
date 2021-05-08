grapesjs.plugins.add('html-block', function(editor, options) {
  options = options || {};

  addHTMLCodeEditor();
  addHTMLCodeComponent();
  // addHTMLCodeBlock();

  function addHTMLCodeEditor() {
    editor.Commands.add("open-html-code-editor", {
      run: function(editor, sender, data) {
        var component = editor.getSelected();

        var codeViewer = editor.CodeManager.getViewer("CodeMirror").clone();
        console.log(codeViewer);
        codeViewer.set({
          codeName: "htmlmixed",
          theme: "hopscotch",
          readOnly: false,
        });

        var modalContent = document.createElement("div");

        var editorTextArea = document.createElement("textarea");

        var saveButton = document.createElement("button");
        saveButton.innerHTML = "Save";
        saveButton.className = "gjs-btn-prim";
        saveButton.style = "margin-top: 8px;";
        saveButton.onclick = function() {
          component.set("content", "");
          component.components(codeViewer.editor.getValue());
          editor.Modal.close();
        };

        modalContent.appendChild(editorTextArea);
        modalContent.appendChild(saveButton);

        codeViewer.init(editorTextArea);

        var htmlContent = document.createElement("div");
        htmlContent.innerHTML = component.toHTML();
        htmlContent = htmlContent.firstChild.innerHTML;
        codeViewer.setContent(htmlContent);

        editor.Modal
          .setTitle("Edit HTML")
          .setContent(modalContent)
          .open();

        codeViewer.editor.refresh();
      }
    });
  };

  function addHTMLCodeComponent() {
    var defaultType = editor.DomComponents.getType('default');

    var _initToolbar = defaultType.model.prototype.initToolbar;

    editor.DomComponents.addType('text', {
      model: defaultType.model.extend({
        initToolbar(args) {
          _initToolbar.apply(this, args);

          var toolbar = this.get("toolbar");
          toolbar.push({
              attributes: { "class": "fa fa-pencil" },
                command: "open-html-code-editor"
          });
          this.set("toolbar", toolbar);
        }
      }, {
        isComponent: function(el) {
          // if (typeof el.hasAttribute == "function" && el.hasAttribute("data-html-code")) {
            return {type: "text"};
          // }
        }
      }),
      view: defaultType.view
    });

  };

  function addHTMLCodeBlock() {
    editor.BlockManager.add("html-code", {
      attributes: {class: "fa fa-code"},
      label: "HTML Code",
      content: '<div data-html-code>Edit my HTML content</div>'
    });
  };

});
