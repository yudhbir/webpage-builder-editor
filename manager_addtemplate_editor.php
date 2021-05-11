<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Html Page Builder</title>
    <script type="text/javascript" src="webeditor2/css_inline_transformer.js"></script>
    <link rel="stylesheet" href="webeditor2/dist/css/grapes.min.css">
    <script src="webeditor2/dist/grapes.min.js"></script>
    <link href="webeditor2/dist/plugins/webpage/dist/grapesjs-preset-webpage.min.css" rel="stylesheet"/>
    <script src="webeditor2/dist/plugins/webpage/dist/grapesjs-preset-webpage.min.js"></script>
    <script src="webeditor2/dist/plugins/html-block/grapesjs-html-block.js"></script>
    <script type="text/javascript" src="js/jquery-2.1.1.js"></script>
    
    <style>
       body,html {height: 100%;margin: 0;}
      .gjs-one-bg {background-color: #2d4573 !important;}  
      .gjs-block{color:#ffffff !important; border:1px solid #ffffff !important;}  
      .gjs-block:hover{}
      .gjs-four-color-h:hover{color:#ffffff !important;}
      .gjs-field,.gjs-sm-label,.gjs-four-color,.gjs-two-color{color:#ffffff !important;}
      .gjs-select option, .gjs-field-select option, .gjs-clm-select option, .gjs-sm-select option, .gjs-fields option, .gjs-sm-unit option,.gjs-three-bg{background-color: #2d4573 !important;}  
      .gjs-category-title, .gjs-layer-title, .gjs-block-category .gjs-title, .gjs-sm-sector .gjs-sm-title, .gjs-clm-tags .gjs-sm-title{color:#ffffff;!important;}
      .list-group { display: block; border: 1px solid; margin-bottom: 4px;margin-left: 12px; font-weight: 800; cursor: pointer; }
      .CodeMirror-scroll, .CodeMirror-sizer, .CodeMirror-gutter, .CodeMirror-gutters, .CodeMirror-linenumber{background: #2a2f40 !important;}
    </style>
  </head>
  <body>
    <div id="gjs" style="height:0px; overflow:hidden">
      <div class="panel">
        <h1 class="welcome">Welcome to</h1>
        <div class="big-title">
          <svg class="logo" viewBox="0 0 100 100">
            <path d="M40 5l-12.9 7.4 -12.9 7.4c-1.4 0.8-2.7 2.3-3.7 3.9 -0.9 1.6-1.5 3.5-1.5 5.1v14.9 14.9c0 1.7 0.6 3.5 1.5 5.1 0.9 1.6 2.2 3.1 3.7 3.9l12.9 7.4 12.9 7.4c1.4 0.8 3.3 1.2 5.2 1.2 1.9 0 3.8-0.4 5.2-1.2l12.9-7.4 12.9-7.4c1.4-0.8 2.7-2.2 3.7-3.9 0.9-1.6 1.5-3.5 1.5-5.1v-14.9 -12.7c0-4.6-3.8-6-6.8-4.2l-28 16.2"/>
          </svg>
          <span>GrapesJS</span>
        </div>
        <div class="description">
          This is a demo content from index.html. For the development, you shouldn't edit this file, instead you can
          copy and rename it to _index.html, on next server start the new file will be served, and it will be ignored by git.
        </div>
      </div>      
   
       <style>
        .panel {
          width: 90%;
          max-width: 700px;
          border-radius: 3px;
          padding: 30px 20px;
          margin: 150px auto 0px;
          background-color: #d983a6;
          box-shadow: 0px 3px 10px 0px rgba(0,0,0,0.25);
          color:rgba(255,255,255,0.75);
          font: caption;
          font-weight: 100;
        }

        .welcome {
          text-align: center;
          font-weight: 100;
          margin: 0px;
        }

        .logo {
          width: 70px;
          height: 70px;
          vertical-align: middle;
        }

        .logo path {
          pointer-events: none;
          fill: none;
          stroke-linecap: round;
          stroke-width: 7;
          stroke: #fff
        }

        .big-title {
          text-align: center;
          font-size: 3.5rem;
          margin: 15px 0;
        }

        .description {
          text-align: justify;
          font-size: 1rem;
          line-height: 1.5rem;
        }
      </style>
     </div>
    <script type="text/javascript">
      var editor = grapesjs.init({
        height: '100%',
        showOffsets: 1,
        noticeOnUnload: 0,
        // storageManager: { autoload: 0 },
        container: '#gjs',
        fromElement: true,
        styleManager: {},
        storageManager: {
          id: 'gjs-',  
          autosave: false,
          setStepsBeforeSave: 1,
          type: 'remote',
          urlStore: "<?php echo $this->Html->url(array('controller' => 'contracts', 'action' => 'store_template_editor', 'manager' => true)) ?>",
          urlLoad: "<?php echo $this->Html->url(array('controller' => 'contracts', 'action' => 'store_template_editor', 'manager' => true)) ?>",
          contentTypeJson: true,
          autoload: false
        },
        plugins: ['gjs-preset-webpage','html-block'],
        pluginsOpts: {
          'gjs-preset-webpage': {}
        }
      });
      var panelManager = editor.Panels;
      panelManager.removeButton('views', "open-tm");
      panelManager.removeButton('views', "open-layers");
      // console.log(panelManager.getPanels());
      let editPanel = null
      var newPanel = panelManager.addButton('views',{
          id: 'template_panel',
          attributes: {class: 'fa fa-address-card-o', title: "Template List"},
          active: false,
          command: {
            run: function (editor) {
                    if(editPanel == null){
                      editPanel="";
                      const editMenuDiv = document.createElement('div');
                      var html="";
                      editor.load(function(response){
                          if(response.result=='success' && Object.entries(response.data).length !== 0){
                            // console.log(response.data);
                            var item=response.data;
                              for (var i = 0; i < item.length; i++) {
                                  html+='<div onclick="populate_template(this)" class="list-group"><p class="list-group-item">'+item[i].template_name+'</p><textarea style="display:none" data-type="html">'+item[i].html+'</textarea><textarea style="display:none" data-type="css">'+item[i].css+'</textarea></div>';
                               }                             
                              editMenuDiv.innerHTML = html;
                              const panels = panelManager.getPanel('views-container')
                              // console.log(panels);
                              panels.set('appendContent', editMenuDiv).trigger('change:appendContent')
                              editPanel = editMenuDiv
                              editPanel.style.display = 'block'
                          }
                      });
                      // editor.on('storage:load', function(e) { console.log('Loaded ', e);});
                    }
                },
                stop: function (editor) {
                    if(editPanel != null){
                        editPanel.style.display = 'none'
                        editPanel=null;
                    }
                }
          }
      });
      panelManager.addButton('options',
        [{
          id: 'save-db',
          className: 'fa fa-floppy-o',
          command: 'save-db',
          attributes: {title: 'Save Template'}
        }]
      );
      panelManager.addButton('options',
        [{
          id: 'copy-code',
          className: 'fa fa-copy',
          attributes: {title: 'Copy Template'},
          command(editor) {
                  var codeViewer = editor.CodeManager.getViewer('CodeMirror').clone();
                  codeViewer.set({
                      codeName: 'htmlmixed',
                      readOnly: 0,
                      theme: 'hopscotch',
                      autoBeautify: true,
                      autoCloseTags: true,
                      autoCloseBrackets: true,
                      lineWrapping: true,
                      styleActiveLine: true,
                      smartIndent: true,
                      indentWithTabs: true
                  });
                  var viewer = codeViewer.editor;
                  var modal = editor.Modal;
                  var container = document.createElement('div');
                  var saveButton = document.createElement("button");
                  saveButton.innerHTML = "Copy";
                  saveButton.className = "gjs-btn-prim";
                  saveButton.style = "margin-top: 8px;";
                  modal.setTitle('Edit Code');
                  if (!viewer) {
                      var txtarea = document.createElement('textarea');
                      container.appendChild(txtarea);
                      // container.appendChild(saveButton);
                      codeViewer.init(txtarea);
                      viewer = codeViewer.editor;
                  }
                  var contents=editor.getHtml()+'<style>'+editor.getCss()+'</style>';
                  // var contents = doInline(contents);
                  modal.setContent('');
                  modal.setContent(container);
                  codeViewer.setContent(contents);
                  modal.open();
                  viewer.refresh();                   
                  saveButton.onclick = function() {
                    newCopy(codeViewer.editor.getValue());
                  };               
          }
        }]
      );
      // Add the command
    editor.Commands.add('save-db', {
        run: function(editor, sender)
        {
          sender && sender.set('active', 0); // turn off the button
          var template = prompt("Please Enter Template name:", "Template Name");
          if (template != null && template != "") {
              console.log('Template ',template);
              editor.on('storage:start:store', (objectToStore) => {
                  if (template) {
                    objectToStore.template_name = template;
                  }
              });
              editor.store();              
              // editor.on('storage:store', function(e) { console.log('Stored ', e);}); 
              editor.on('storage:end:store', (response) => {
                  if(response.result=='success'){
                    alert(response.msg);
                  }
              });
          }
          // var htmldata = editor.getHtml();
          // var cssdata = editor.getCss();         
        }
    });  
    // console.log(editor.runCommand('gjs-get-inlined-html'));   
      // editor.on('load', function() {
      //     var panelManager = editor.Panels;
      //     editor.Panels.render([
      //       panelManager.removePanel('options'),
      //       panelManager.removePanel('views'),
      //       panelManager.removePanel('devices-c'),
      //     ]);
      // });
    function populate_template(obj){
        var html_data=$(obj).find('textarea:eq(0)').text();
        var css_data=$(obj).find('textarea:eq(1)').text();
        // console.log(html_data);
        editor.setComponents(html_data);
        editor.setStyle(css_data);
    }
    </script>
  </body>
</html>
