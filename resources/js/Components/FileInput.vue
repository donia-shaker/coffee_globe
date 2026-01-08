<template>
    <div class="col-md-6 my-3">
      <label class="form-label fs-6" :for="fieldName">{{ label }}</label>
      <div class="input-group input-group-merge">
        <input
          :name="fieldName"
          type="file"
          class="form-control border-2 rounded p-2"
          :id="fieldName"
          @change="handleFileChange"
        />
      </div>
      <span v-if="message" class="text-end text-danger">* {{ message }} </span>
      <img v-if="previewSrc" :id="previewId" :src="previewSrc" style="max-height: 100px;">
    </div>
  </template>
  
  <script>
  export default {
    props: {
      modelValue: File, // v-model support
      fieldName: {
        type: String,
        required: true
      },
      label: {
        type: String,
        required: true
      },
      previewId: {
        type: String,
        default: "preview-image"
      },
      src: {
        type: String,
        default: ""
      },
      message: {
        type: String,
        default: ""
      }
    },
    data() {
      return {
        previewSrc: this.src || '' // Initialize with src, if any
      };
    },
    watch: {
      // Watch for changes to the `src` prop to update the preview
      src(newSrc) {
        if (newSrc) {
          this.previewSrc = newSrc;
        }
      }
    },
    methods: {
      handleFileChange(event) {
        const file = event.target.files[0];
        if (file) {
          this.$emit("update:modelValue", file); // Emit the selected file via v-model
          this.previewSrc = URL.createObjectURL(file); // Create a preview URL
        }
      }
    }
  };
  </script>
  