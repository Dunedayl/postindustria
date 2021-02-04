<template>
  <div class="profile container">
    <ProfileHeader />
    <ProfileBody />
  </div>
</template>


<script>
// @ is an alias to /src
import ProfileHeader from "@/components/profile/Profile.header.vue";
import ProfileBody from "@/components/profile/Profile.body.vue";

export default {
  name: "ProfilePage",
  async beforeCreate() {

  },
  async created() {
  },
  components: {
    ProfileHeader,
    ProfileBody,
  },
};
</script>