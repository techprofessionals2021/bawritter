<template>
    <div class="my-component">
      <h1>{{ title }}</h1>
      <p>{{ message }}</p>
    </div>
  </template>
  
  <script>
  export default {
    name: 'MyComponent',
    props: {
      title: {
        type: String,
        required: true,
      },
      message: {
        type: String,
        default: 'This is a simple Vue component.',
      },
    },
  };
  </script>
  
  <style scoped>
  .my-component {
    border: 1px solid #ccc;
    padding: 20px;
    margin-bottom: 20px;
  }
  
  h1 {
    color: #42b983;
  }
  </style>