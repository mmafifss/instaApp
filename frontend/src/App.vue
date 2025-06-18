<script setup>
import HelloWorld from "./components/HelloWorld.vue";
</script>

<template>
  <div id="app" class="min-h-screen bg-gray-100 p-4">
    <Register />
    <Login />
    <PostFeed />
  </div>
</template>

<script setup>
import Register from "./components/Register.vue";
import Login from "./components/Login.vue";
import PostFeed from "./components/PostFeed.vue";
</script>

<style>
body {
  font-family: system-ui, Avenir, Helvetica, Arial, sans-serif;
  background-color: #f0f0f0;
  margin: 0;
  padding: 0;
}
</style>
