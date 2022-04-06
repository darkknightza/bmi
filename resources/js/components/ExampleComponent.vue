<template>
    <div class="container">
        <div class="row">
            <div style="padding-bottom: 20px;padding-left: 30px">
                <select class="form-control" v-model="selected" @change="disPlayChart">
                    <option value="height">ส่วนสูง</option>
                    <option value="weight">น้ำหนัก</option>
                    <option value="bmi">BMI</option>
                </select>
            </div>
        </div>
        <div class="row">
            <canvas id="lineChart" height="450" width="800"></canvas>
        </div>
    </div>
</template>

<script>
  import Chart from 'chart.js';
  import axios from 'axios';

  export default {
    data() {
      return {
        data_chart: [],
        chart: null,
        selected: 'height'
      }
    },
    mounted() {
      this.getChart()
    },
    methods: {

      getChart() {
        this.data_chart = []
        let that = this
        axios.get('/get_chart_bmi').then(function (response) {
          if (response.data.success) {
            that.data_chart = response.data.data;
            that.disPlayChart()
          }
        }).catch(function (error) {
            console.log(error)
        });
      },
      disPlayChart(){
        if(this.chart){
          this.chart.destroy()
        }
        let data = []
        let label = ''
        if(this.selected === 'height'){
          label = 'ส่วนสูง (ซม.)'
          data = this.data_chart.height
        }else if(this.selected === 'weight'){
          label = 'น้ำหนัก (กก.)'
          data = this.data_chart.weight
        }else if(this.selected === 'bmi'){
          label = 'BMI (กก./ม.^2)'
          data = this.data_chart.bmi
        }
        var ctx = document.getElementById('lineChart').getContext('2d')
        this.chart = new Chart(ctx, {
          type: 'line',
          data: {
            labels: this.data_chart.label,
            datasets: [{
                label: label,
                yAxisID: '111',
                fill: false,
                borderColor: "#1a8cff",
                backgroundColor: "#1a8cff",
                borderJoinStyle: "round",
                pointRadius: 1,
                pointHoverRadius: 5,
                pointBackgroundColor: "#1a8cff",
                spanGaps: false,
                borderWidth: 2,
                data: data
              }]
          },
          options: {
            animation: {
              duration: 0
            },
            responsive: true,
            elements: {
              line: {
                tension: 0
              }
            },
            legend: {
              position: 'bottom',
              fullWidth: false,
              labels: {
                fontSize: 12,
                boxWidth: 10,
                usePointStyle: true
              }
            },
            plugins: {
              datalabels: {
                display: false
              }
            },
            scales: {
              xAxes: [{
                gridLines: {
                  display: true,
                  color: '#808080'
                },
              }],
              yAxes: [{
                id: "111",
                position: "left",
                scaleLabel: {
                  "display": true,
                  "labelString": label
                }
              }]
            }
          }
        })
      }
    }
  }
</script>
