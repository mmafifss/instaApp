<script setup>
import HelloWorld from "./components/HelloWorld.vue";
</script>

<template>
  <div>
    <a href="https://vite.dev" target="_blank">
      <img src="/vite.svg" class="logo" alt="Vite logo" />
    </a>
    <a href="https://vuejs.org/" target="_blank">
      <img src="./assets/vue.svg" class="logo vue" alt="Vue logo" />
    </a>
  </div>
  <HelloWorld msg="Vite + Vue" />
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
</template>

<style scoped>
.logo {
  height: 6em;
  padding: 1.5em;
  will-change: filter;
  transition: filter 300ms;
}
.logo:hover {
  filter: drop-shadow(0 0 2em #646cffaa);
}
.logo.vue:hover {
  filter: drop-shadow(0 0 2em #42b883aa);
}
</style>
