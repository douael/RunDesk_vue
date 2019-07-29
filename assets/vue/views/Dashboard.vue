<template>
  <div>
    <div class="row col darkBlue-bg green no-margin">
      <h1>Tableau de bord</h1>
    </div>

    <section class="row col text-center placeholders pt-3">

      <div class="col-6 col-sm-3 placeholder">
        <b-card title="Catégories" style="max-width: 20rem;" class="mb-2 darkblue green-bg">
          <b-card-text>
            Nombres de Catégories
          </b-card-text>

          <b-progress :max="10" height="2rem">
            <b-progress-bar :value="10" variant="info">
              <strong>{{ categorys.length }}</strong>
            </b-progress-bar>
          </b-progress>
          <br/>
          <b-button href="/categorys" class="purple-bg">Voir plus</b-button>
        </b-card>
      </div>

      <div class="col-6 col-sm-3 placeholder">
        <b-card title="Matériels" style="max-width: 20rem;" class="mb-2 darkblue green-bg">
          <b-card-text>
            Matériels disponible
          </b-card-text>

          <b-progress :max="materials.length" height="2rem">
            <b-progress-bar :value="countAvailableMaterials" variant="info">
              
            </b-progress-bar>
          </b-progress>
          <strong>{{ countAvailableMaterials }} / {{ materials.length }}</strong>
          <br/>

          <b-button href="/materials" class="purple-bg">Voir plus</b-button>
        </b-card>
      </div>
      <div class="col-6 col-sm-3 placeholder">
        <b-card title="Emprunts" style="max-width: 20rem;" class="mb-2 darkblue green-bg">
          <b-card-text>
            Emprunts en cours
          </b-card-text>

          <b-progress :max="borrowings.length" height="2rem">
            <b-progress-bar :value="materials.length - countAvailableMaterials" variant="info">
            </b-progress-bar>
          </b-progress>
             <strong>{{ materials.length - countAvailableMaterials }} / {{ borrowings.length }}</strong>
          <br/>

          <b-button href="/borrowings" class="purple-bg">Voir plus</b-button>
        </b-card>
      </div>
      <div class="col-6 col-sm-3 placeholder">
        <b-card title="Employés" style="max-width: 20rem;" class="mb-2 darkblue green-bg">
          <b-card-text>
            Nombre d'employés
          </b-card-text>

          <b-progress :max="10" height="2rem">
            <b-progress-bar :value="10" variant="info">
              <strong>{{ employees.length }}</strong>
            </b-progress-bar>
          </b-progress>
          <br/>

          <b-button href="/employees" class="purple-bg">Voir plus</b-button>
        </b-card>
      </div>
    </section>
<div v-if="isLoading" class="row col">
        <div class="container">
	<div class="row">
		<div class="container">
	<div class="row">
	<a href="#" class="intro-banner-vdo-play-btn pinkBg" target="_blank">
<i class="glyphicon glyphicon-play whiteText" aria-hidden="true"></i>
<span class="ripple pinkBg"></span>
<span class="ripple pinkBg"></span>
<span class="ripple pinkBg"></span>
</a>
	</div>
</div>

</div>
</div>
    </div>

    
    <h2>Dernières actions</h2>
    <div v-if="!hasLogs && !isLoading" class="row col">
        Pas d'actions enregistrées !
    </div>
     <dashboard></dashboard>

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
    
    isLoading () {
        return this.$store.getters['dashboard/isLoading']
    },
    hasLogs () {
      return this.$store.getters['dashboard/hasDashboards'];
    },
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
    countAvailableMaterials(){
      var array = this.$store.getters['material/materials'];
      var count = 0;
      for(var i = 0; i < array.length; ++i){
          if(array[i]['available'] == true)
              count++;
      }
      return count;
    }
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
    },
  }
}

</script>

<style>
.small {
  max-width: 600px;
  margin:  150px auto;
}
</style>