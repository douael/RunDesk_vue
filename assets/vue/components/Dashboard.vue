<template>
  <div class="overflow-auto">
    <b-pagination
      v-model="currentPage"
      :total-rows="rows"
      :per-page="perPage"
      aria-controls="my-table"
    ></b-pagination>

    <p class="mt-3">Current Page: {{ currentPage }}</p>

    <b-table striped hover
      id="my-table"
      :items="dashboards.slice(0, 100)"
      :per-page="perPage"
      :current-page="currentPage"
      small
    >
    </b-table>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        perPage: 10,
        currentPage: 1,
      }
    },
    created () {
    this.$store.dispatch('dashboard/fetchDashboards');

  },
    computed: {
      rows() {
        return this.dashboards.length
      },
      dashboards () {
      return this.$store.getters['dashboard/dashboards'];
    },
    }
  }
</script>