<template>
  <div>
    <div class="row col darkBlue-bg green no-margin">
      <h1>Tableau de bord</h1>
    </div>

    <section class="row col text-center placeholders pt-3">

      <div class="col-6 col-sm-3 placeholder">
        <b-card title="Catégories" style="max-width: 20rem;" class="mb-2 darkblue green-bg">
          <b-card-text>
            {{ categorys.length }} Catégories
          </b-card-text>

          <b-progress :max="10" height="2rem">
            <b-progress-bar :value="10" variant="info">
              Total : <strong>{{ categorys.length }}</strong>
            </b-progress-bar>
          </b-progress>
          <br/>
          <b-button href="/categorys" class="purple-bg">Voir plus</b-button>
        </b-card>
      </div>

      <div class="col-6 col-sm-3 placeholder">
        <b-card title="Matériels" style="max-width: 20rem;" class="mb-2 darkblue green-bg">
          <b-card-text>
            {{ materials.length }} Matériels
          </b-card-text>

          <b-progress :max="10" height="2rem">
            <b-progress-bar :value="10" variant="info">
              Disponibles : <strong>{{ materials.length-borrowings.length }} / {{ materials.length }}</strong>
            </b-progress-bar>
          </b-progress>
          <br/>

          <b-button href="/materials" class="purple-bg">Voir plus</b-button>
        </b-card>
      </div>
      <div class="col-6 col-sm-3 placeholder">
        <b-card title="Emprunts" style="max-width: 20rem;" class="mb-2 darkblue green-bg">
          <b-card-text>
            {{ borrowings.length }} Emprunts
          </b-card-text>

          <b-progress :max="10" height="2rem">
            <b-progress-bar :value="10" variant="info">
              Empruntés : <strong>{{ borrowings.length }} / {{ materials.length }}</strong>
            </b-progress-bar>
          </b-progress>
          <br/>

          <b-button href="/borrowings" class="purple-bg">Voir plus</b-button>
        </b-card>
      </div>
      <div class="col-6 col-sm-3 placeholder">
        <b-card title="Employés" style="max-width: 20rem;" class="mb-2 darkblue green-bg">
          <b-card-text>
            {{ employees.length }} Employés
          </b-card-text>

          <b-progress :max="10" height="2rem">
            <b-progress-bar :value="10" variant="info">
              Total : <strong>{{ employees.length }}</strong>
            </b-progress-bar>
          </b-progress>
          <br/>

          <b-button href="/employees" class="purple-bg">Voir plus</b-button>
        </b-card>
      </div>
    </section>

    <h2>Dernières actions</h2>
     <dashboard ></dashboard>


  </div>
</template>
<script>

//import ChartCard from '../components/Cards/ChartCard.vue'
import StatsCard from '../components/Cards/StatsCard.vue'
import LTable from '../components/Table.vue'
import Dashboard from '../components/Dashboard'

import { Line } from 'vue-chartjs'
import LineChart from '../public/LineChart.js'


export default {
  components: {
    LineChart,Dashboard
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