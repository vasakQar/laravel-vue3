<template>
  <div class="container mt-4">
    <h1>Create New Blog</h1>
    <form @submit.prevent="submitBlog">
      <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input type="text" id="title" v-model="title" class="form-control" required>
      </div>
      <div class="mb-3">
        <label for="image" class="form-label">Image</label>
        <input type="file" id="image" @change="handleFileUpload" class="form-control" required>
      </div>
      <div class="mb-3">
        <label for="content" class="form-label">Content</label>
        <textarea id="content" v-model="content" class="form-control" rows="5" required></textarea>
      </div>
      <button type="submit" class="btn btn-primary">Create Blog</button>
    </form>
  </div>
</template>

<script>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import api from '../axios';

export default {
  name: 'CreateBlog',
  setup() {
    const title = ref('');
    const image = ref(null);
    const content = ref('');
    const router = useRouter();

    const handleFileUpload = (event) => {
      image.value = event.target.files[0];
    };

    const submitBlog = async () => {
      const formData = new FormData();
      formData.append('title', title.value);
      formData.append('image', image.value);
      formData.append('content', content.value);

      try {
        await api.post('/blogs', formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
          },
        });
        await router.push('/home');
      } catch (error) {
        console.error('Error creating blog:', error);
      }
    };

    return {
      title,
      image,
      content,
      handleFileUpload,
      submitBlog,
    };
  },
};
</script>

<style scoped>
.container {
  max-width: 800px;
}
</style>
