<template>
    <div class="container">
        <canvas id="lineChart" height="450" width="800"></canvas>
    </div>
</template>

<script>
  import Chart from 'chart.js';
  import axios from 'axios';

  export default {
    data() {
      return {
        data_chart: []
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
        var ctx = document.getElementById('lineChart').getContext('2d')
        this.chartCandleStick = new Chart(ctx, {
          type: 'line',
          data: {
            labels: this.data_chart.label,
            datasets: [
              {
                label: 'ส่วนสูง',
                yAxisID: '111',
                fill: false,
                borderColor: "#FFEB3B",
                backgroundColor: "#FFEB3B",
                borderJoinStyle: "round",
                pointRadius: 1,
                pointHoverRadius: 5,
                pointBackgroundColor: "#FFEB3B",
                spanGaps: false,
                borderWidth: 2,
                data: this.data_chart.height
              },
              {
                yAxisID: '222',
                label: 'น้ำหนัก',
                fill: false,
                borderColor: "#00BCD4",
                backgroundColor: "#00BCD4",
                borderJoinStyle: "round",
                pointRadius: 1,
                pointHoverRadius: 5,
                pointBackgroundColor: "#00BCD4",
                spanGaps: false,
                borderWidth: 2,
                data: this.data_chart.weight
              }, {
                yAxisID: '333',
                label: 'BMI',
                fill: false,
                borderColor: "#9400D3",
                backgroundColor: "#9400D3",
                borderJoinStyle: "round",
                pointRadius: 1,
                pointHoverRadius: 5,
                pointBackgroundColor: "#9400D3",
                spanGaps: false,
                borderWidth: 2,
                data: this.data_chart.bmi
              }
            ]
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
                  "labelString": "น้ำหนัก"
                }
              }, {
                id: "222",
                position: "right",
                scaleLabel: {
                  "display": true,
                  "labelString": "ส่วนสูง"
                }
              }, {
                id: "333",
                position: "right",
                scaleLabel: {
                  "display": true,
                  "labelString": "BMI"
                }
              }]
            }
          }
        })
      }
    }
  }
</script>
