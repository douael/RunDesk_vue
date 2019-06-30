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


         <!-- {{dashboards}}-->

          <table class="table table-striped">
              <thead>
                <tr>
                  <th>Logs</th>
                  <th>Time</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="dashboard in dashboards">
                  <td>{{ dashboard[0] }}</td>
                  <td>{{ dashboard[1] }}</td>
                </tr>
              </tbody>
            </table>


            <div class="content">
              <div class="col-6 col-sm-3 placeholder">
                <line-chart :chart-data="datacollection"></line-chart>
                <button @click="fillData()">Randomize</button>
              </div>
            </div>
          <h2>Section title</h2>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Header</th>
                  <th>Header</th>
                  <th>Header</th>
                  <th>Header</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>1,001</td>
                  <td>Lorem</td>
                  <td>ipsum</td>
                  <td>dolor</td>
                  <td>sit</td>
                </tr>
                <tr>
                  <td>1,002</td>
                  <td>amet</td>
                  <td>consectetur</td>
                  <td>adipiscing</td>
                  <td>elit</td>
                </tr>
                <tr>
                  <td>1,003</td>
                  <td>Integer</td>
                  <td>nec</td>
                  <td>odio</td>
                  <td>Praesent</td>
                </tr>
                <tr>
                  <td>1,003</td>
                  <td>libero</td>
                  <td>Sed</td>
                  <td>cursus</td>
                  <td>ante</td>
                </tr>
                <tr>
                  <td>1,004</td>
                  <td>dapibus</td>
                  <td>diam</td>
                  <td>Sed</td>
                  <td>nisi</td>
                </tr>
                <tr>
                  <td>1,005</td>
                  <td>Nulla</td>
                  <td>quis</td>
                  <td>sem</td>
                  <td>at</td>
                </tr>
                <tr>
                  <td>1,006</td>
                  <td>nibh</td>
                  <td>elementum</td>
                  <td>imperdiet</td>
                  <td>Duis</td>
                </tr>
                <tr>
                  <td>1,007</td>
                  <td>sagittis</td>
                  <td>ipsum</td>
                  <td>Praesent</td>
                  <td>mauris</td>
                </tr>
                <tr>
                  <td>1,008</td>
                  <td>Fusce</td>
                  <td>nec</td>
                  <td>tellus</td>
                  <td>sed</td>
                </tr>
                <tr>
                  <td>1,009</td>
                  <td>augue</td>
                  <td>semper</td>
                  <td>porta</td>
                  <td>Mauris</td>
                </tr>
                <tr>
                  <td>1,010</td>
                  <td>massa</td>
                  <td>Vestibulum</td>
                  <td>lacinia</td>
                  <td>arcu</td>
                </tr>
                <tr>
                  <td>1,011</td>
                  <td>eget</td>
                  <td>nulla</td>
                  <td>Class</td>
                  <td>aptent</td>
                </tr>
                <tr>
                  <td>1,012</td>
                  <td>taciti</td>
                  <td>sociosqu</td>
                  <td>ad</td>
                  <td>litora</td>
                </tr>
                <tr>
                  <td>1,013</td>
                  <td>torquent</td>
                  <td>per</td>
                  <td>conubia</td>
                  <td>nostra</td>
                </tr>
                <tr>
                  <td>1,014</td>
                  <td>per</td>
                  <td>inceptos</td>
                  <td>himenaeos</td>
                  <td>Curabitur</td>
                </tr>
                <tr>
                  <td>1,015</td>
                  <td>sodales</td>
                  <td>ligula</td>
                  <td>in</td>
                  <td>libero</td>
                </tr>
              </tbody>
            </table>
          </div>
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