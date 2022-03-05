function ChunkUploader(params) {

  const {form, submitButton, input, mimeTypes, container, multipartParams, required, url} = params;

  this.form = form;
  this.submitButton = submitButton;
  this.input = input;

  const inputChangeEvent = document.createEvent("UIEvents");
        inputChangeEvent.initUIEvent("change", true, true);

  this.uploader = new plupload.Uploader({
      browse_button: container.find('.browse')[0], // this can be an id of a DOM element or the DOM element itself
      url,
      chunks_size: '30mb',
      multi_selection: false,
      max_retries: 3,
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      filters: {
        mime_types : mimeTypes
      },
      multipart_params: multipartParams
  });

  this.uploader.init();

  this.currentFile = null;

  this.startUploader = (file) => {
      // trigger stopUploader first
      this.stopUploader();

      this.currentFile = file;
      container.find('.selected_file').text(`${this.currentFile.name} (${plupload.formatSize(this.currentFile.size)})`);
      this.uploader.start();

      // disable the browse button
      container.find('.browse').attr('disabled', true);
      this.uploader.disableBrowse();

      // show the cancle upload option
      container.find('.cancel_upload').show();

  }
  this.stopUploader = () => {
      // clear the video input value
      this.input.val('');
      this.input[0].dispatchEvent(inputChangeEvent);

      // disable the submit button
      this.submitButton.attr('disabled', true);

      // stop the uploader
      this.uploader.stop();

      // removes the currentFile
      if (this.currentFile) {
          this.uploader.removeFile(this.currentFile);
      }

      // enable the browse button
      container.find('.browse').attr('disabled', false);
      this.uploader.disableBrowse(false);

      container.find('.selected_file').text('');
      container.find('.upload_status').text('');
      container.find('.cancel_upload').hide();
  }

  //const enableForm()

  this.uploader.bind('FilesAdded', (up, files) => {
      this.startUploader(files[0]);
  });

  this.uploader.bind('FileUploaded', (up, files, response) => {
      const responseObject = JSON.parse(response.response);
      const result = responseObject.result

      if(result.ready) {
        this.input.val(result.file);
        this.input[0].dispatchEvent(inputChangeEvent);

        // disable the submit button if required attribute
        this.submitButton.attr('disabled', false);
      }
  });

  container.find('.cancel_upload').click(() => {
      if(confirm('Are you sure you want to select another video?')) {
          this.stopUploader();
          if(!required) {
            this.submitButton.attr('disabled', false);
          }
      }
  })

  this.uploader.bind('UploadProgress', (up, file) => {
      container.find('.upload_status').text(`${file.percent}%`);
  });

  this.uploader.bind('ChunkUploaded', (up, file, info) => {
      console.log('completed');
      // do some chunk related stuff
  });

  this.uploader.bind('Error', (up, err) => {
      document.getElementById('upload_status').innerHTML += "\nError #" + err.code + ": " + err.message;
  });

    if(typeof required !== 'undefined' && required) {
        this.form.on('submit', () => {
            return (this.input.val() !== '');
        })
    }

}