<template>
  <div>
    <h1>{{ message }}</h1>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      message: ''
    };
  },
  created() {
    axios.get('http://localhost:8000/api/data')
        .then(response => {
          this.message = response.data.message;
        })
        .catch(error => {
          console.error('There was an error!', error);
        });
  }
};
</script>
