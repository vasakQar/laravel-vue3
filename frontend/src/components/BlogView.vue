<template>
  <div class="container mt-4">
    <button class="btn btn-secondary mb-3" @click="goBack">
      <i class="bi bi-arrow-left"></i> Go Back
    </button>
    <div v-if="blog" class="card">
      <img v-if="!isEditing" :src="baseUrl + blog.image" alt="Blog Image" class="card-img-top">
      <div class="card-body">
        <div v-if="isEditing">
          <input type="text" v-model="editableBlog.title" class="form-control mb-3" />
          <textarea v-model="editableBlog.content" class="form-control mb-3" rows="5"></textarea>
          <input type="file" @change="handleFileUpload" class="form-control mb-3" />
        </div>
        <div v-else>
          <h1 class="card-title">{{ blog.title }}</h1>
          <p class="card-text">{{ blog.content }}</p>
        </div>
        <div v-if="isOwner" class="mt-3">
          <button v-if="!isEditing" class="btn btn-warning me-2" @click="isEditing = true">Edit</button>
          <button v-if="isEditing" class="btn btn-success me-2" @click="updateBlog">Save</button>
          <button v-if="isEditing" class="btn btn-secondary me-2" @click="cancelEdit">Cancel</button>
          <button class="btn btn-danger" @click="deleteBlog">Delete</button>
        </div>
      </div>
    </div>
    <div v-else>
      <p>Loading...</p>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import api from '../axios';
import { backendUrl } from '../axios';

export default {
  name: 'BlogView',
  setup() {
    const blog = ref(null);
    const editableBlog = ref({title: '', content: '', image: null});
    const route = useRoute();
    const router = useRouter();
    const baseUrl = ref(backendUrl);
    const isOwner = ref(false);
    const isEditing = ref(false);

    const fetchBlog = async () => {
      try {
        const response = await api.get(`/blogs/${route.params.id}`, {
          headers: {
            'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
          }
        });
        blog.value = response.data.data;
        const userId = localStorage.getItem('user_id');
        isOwner.value = blog.value.user_id === parseInt(userId);
      } catch (error) {
        console.error('Error fetching blog:', error);
      }
    };

    const handleFileUpload = (event) => {
      editableBlog.value.image = event.target.files[0];
    };

    const updateBlog = async () => {
      const formData = new FormData();
      formData.append('title', editableBlog.value.title);
      formData.append('content', editableBlog.value.content);
      if (editableBlog.value.image) {
        formData.append('image', editableBlog.value.image);
      }



      try {
        await api.post(`/blogs/${route.params.id}?_method=PUT`, formData, {
          headers: {
            'Authorization': `Bearer ${localStorage.getItem('auth_token')}`,
            'Content-Type': 'multipart/form-data',
          },
        });
        isEditing.value = false;
        await fetchBlog();
      } catch (error) {
        console.error('Error updating blog:', error);
      }
    };

    const cancelEdit = () => {
      isEditing.value = false;
      editableBlog.value = {...blog.value, image: null};
    };

    const deleteBlog = async () => {
      try {
        await api.delete(`/blogs/${route.params.id}`, {
          headers: {
            'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
          }
        });
        await router.push('/home');
      } catch (error) {
        console.error('Error deleting blog:', error);
      }
    };

    const goBack = () => {
      router.back();
    };

    onMounted(async () => {
      await fetchBlog();
      editableBlog.value = {...blog.value, image: null};
    });

    return {
      blog,
      editableBlog,
      baseUrl,
      isOwner,
      isEditing,
      handleFileUpload,
      updateBlog,
      cancelEdit,
      deleteBlog,
      goBack,
    };
  },
};
</script>

<style scoped>
.container {
  max-width: 800px;
}

.card {
  border: 1px solid #ddd;
  border-radius: 8px;
  overflow: hidden;
}

.card-img-top {
  object-fit: cover;
  height: 300px;
}

.card-body {
  padding: 20px;
}

.card-title {
  font-size: 2rem;
  margin-bottom: 20px;
}

.card-text {
  font-size: 1.2rem;
  line-height: 1.6;
}
</style>
