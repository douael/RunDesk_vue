<template>
        <div>
          <h1>Dashboard</h1>

          <section class="row text-center placeholders">

            <div class="col-6 col-sm-3 placeholder">
              <b-card title="Categories" style="max-width: 20rem;" class="mb-2 darkblue green-bg">
                <b-card-text>
                  {{ categorys.length }} Categories
                </b-card-text>

                <b-button href="/categorys" class="darkblue-bg">Show more</b-button>
              </b-card>
            </div>

            <div class="col-6 col-sm-3 placeholder">
              <b-card title="Materials" style="max-width: 20rem;" class="mb-2 darkblue green-bg">
                <b-card-text>
                    {{ materials.length }} materials
                </b-card-text>

                <b-button href="/materials" class="darkblue-bg">Show more</b-button>
              </b-card>
            </div>
            <div class="col-6 col-sm-3 placeholder">
              <b-card title="Borrows" style="max-width: 20rem;" class="mb-2 darkblue green-bg">
                <b-card-text>
                  {{ borrowings.length }} borrows
                </b-card-text>

                <b-button href="/borrowing" class="darkblue-bg">Show more</b-button>
              </b-card>
            </div>
            <div class="col-6 col-sm-3 placeholder">
              <b-card title="Employees" style="max-width: 20rem;" class="mb-2 darkblue green-bg">
                <b-card-text>
                  {{ employees.length }} Employees
                </b-card-text>

                <b-button href="/employees" class="darkblue-bg">Show more</b-button>
              </b-card>
            </div>
          </section>

            <div class="content">
              <div class="col-6 col-sm-3 placeholder">
                <line-chart :chart-data="datacollection"></line-chart>
                <button @click="fillData()">Randomize</button>
              </div>
            </div>

            <h2>Logs</h2>
          <table class="table table-striped">
              <thead>
                <tr>
                  <th>Actions</th>
                  <th>Time</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="dashboard in dashboards.slice().reverse()">
                  <td v-html="dashboard[0]">{{ dashboard[0] }}</td>
                  <td>{{ dashboard[1] }}</td>
                </tr>
              </tbody>
            </table>


       </div>
</template>
<script>

  //import ChartCard from '../components/Cards/ChartCard.vue'
  import StatsCard from '../components/Cards/StatsCard.vue'
  import LTable from '../components/Table.vue'

  import { Line } from 'vue-chartjs'
  import LineChart from '../public/LineChart.js'


  export default {
    components: {
      LineChart
    },
    data () {
      return {
        datacollection: null
      }
    },
    mounted () {
      this.fillData()
    },
    created () {
      this.$store.dispatch('material/fetchMaterials');
      this.$store.dispatch('category/fetchCategorys');
      this.$store.dispatch('employee/fetchEmployees');
      this.$store.dispatch('borrowing/fetchBorrowings');
      this.$store.dispatch('dashboard/fetchDashboards');

    },
    computed: {
            materials () {
                return this.$store.getters['material/materials'];
            },
            categorys () {
                return this.$store.getters['category/categorys'];
            },
            employees () {
                return this.$store.getters['employee/employees'];
            },
            borrowings () {
                return this.$store.getters['borrowing/borrowings'];
            },
            dashboards () {
                return this.$store.getters['dashboard/dashboards'];
            },
    },
    methods: {
      fillData () {
        this.datacollection = {
          labels: [this.getRandomInt(), this.getRandomInt()],
          datasets: [
            {
              label: 'Data One',
              backgroundColor: '#f87979',
              data: [this.getRandomInt(), this.getRandomInt()]
            }, {
              label: 'Data One',
              backgroundColor: '#f87979',
              data: [this.getRandomInt(), this.getRandomInt()]
            }
          ]
        }
      },
      getRandomInt () {
        return Math.floor(Math.random() * (50 - 5 + 1)) + 5
      }
    }
  }
</script>

<style>
  .small {
    max-width: 600px;
    margin:  150px auto;
  }
</style>