<script setup >
import {ref, onMounted, } from 'vue';
import axios from 'axios';
import { MapboxSearchBox } from '@mapbox/search-js-web'

const lat = ref(22.62);
const lon = ref(120.312);
const cityName = ref("Kaohsiung")

const dayForecast = ref([]);
const weather = ref(null);


const cityDay = ref('');
const cityTime = ref('');
const isLoading = ref(true);

const searchBox = () => {
    const searchBoxElement = new MapboxSearchBox();
    searchBoxElement.accessToken = import.meta.env.VITE_GEOCODING_API_KEY;

    document.getElementById('search-box-container').appendChild(searchBoxElement);
    searchBoxElement.addEventListener('retrieve', (e) => {
        if(e.detail.features[0].geometry.coordinates){
            lat.value = e.detail.features[0].geometry.coordinates[1];
            lon.value = e.detail.features[0].geometry.coordinates[0];
            cityName.value = e.detail.features[0].properties.name;
            fetchWeather();
            fetchDayForecast();
        }else{
            console.log("Invalid Location Latitude and Longitude");
        }
    })
};

const fetchWeather = async () => {
    isLoading.value = true;
    try{
        const response = await axios.get('/api/weather', {
            params: {
                lat: lat.value,
                lon: lon.value
            }

        });
        weather.value = response.data
        timeZone();
    }catch (errors){
        console.log('Error Fetching Weather Data', errors);
    }
};

const fetchDayForecast = async () => {
  try{
      const response = await axios.get('/api/forecast', {
          params: {
              lat: lat.value,
              lon: lon.value
          }
      })
      const forecastList = response.data.list
      const groupedForecasts = {}

      forecastList.forEach((item) => {
          const date = item.dt_txt.split(" ")[0]
          if(!groupedForecasts[date]){
              groupedForecasts[date] = []
          }
          groupedForecasts[date].push(item)
      })
      const dailyForecasts = Object.values(groupedForecasts).slice(1, 6);

      console.log("Grouped Forecast Data:", dailyForecasts);

      dayForecast.value = dailyForecasts; // Store grouped data
  }catch(errors){
      console.log('Error Fetching Weather Data', errors);
  }finally {
      isLoading.value = false;
  }
};

const timeZone = () => {
    const unixTime = weather.value.dt + weather.value.timezone;
    const dateObj = new Date(unixTime * 1000);
    const dayStr = new Intl.DateTimeFormat('en-US', {weekday: 'long', timeZone: 'UTC'}).format(dateObj);
    const timeStr = new Intl.DateTimeFormat('en-US',{hour: 'numeric', minute: 'numeric', timeZone: 'UTC'}).format(dateObj);

    cityDay.value = dayStr;
    cityTime.value = timeStr;
}

onMounted(() => {
    searchBox();
    fetchWeather();
    fetchDayForecast();
});

</script>

<template>
    <nav id='search-box-container' class='w-4/6 mx-auto mt-20 mb-10'>
        <h1 class='text-4xl text-center my-5 font-bold'>KEVIN WEATHER FORECAST</h1>
    </nav>

    <main id='weather-container' class='w-5/6 m-auto grid grid-cols-2 gap-4 ' v-if='!isLoading'>
        <section id='todayData' class=' grid md:grid-cols-2 grid-cols-5 gap-4 col-span-2 ' >

            <div id='mainData' class='border p-5 flex justify-between items-center col-span-2 md:col-span-1'>
                <div id="data">
                    <p class='font-bold text-2xl'> {{cityName}}</p>
                    <p>{{cityDay}} {{cityTime}}</p>
                    <p>{{weather.weather[0].description}}</p>
                </div>

                <div id="icon">
                    <img :src="`http://openweathermap.org/img/wn/${weather.weather[0].icon}.png`" alt="">
                </div>

            </div>

            <div id='tempData' class='border grid grid-cols-2 text-center p-5 gap-2'>
                <p class='col-span-2 m-auto text-5xl'>{{Math.round(weather.main.temp)}}°</p>
                <p class='md:text-right'>H: {{Math.round(weather.main.temp_max)}}°</p>
                <p class='text-center'>L: {{Math.round(weather.main.temp_min)}}°</p>
            </div>

            <div id='otherData' class='grid grid-cols-3 col-span-2 gap-4'>
                <div id="humidityData" class='border p-5'>
                    <p>Humidity</p>
                    <p class=''>{{weather.main.humidity}}%</p>
                </div>

                <div id="windData" class='border p-5'>
                    <p>Wind</p>
                    <p class=''>{{Math.round(weather.wind.speed * 3.6)}} Km/H</p>
                    <p class='border-t-2'>{{weather.wind.deg}}°</p>
                </div>

                <div id="windData" class='border p-5'>
                    <p>Visibility</p>
                    <p class=''>{{weather.visibility / 1000 }} Km</p>
                </div>
            </div>
        </section>


        <section id='forcastData' class='grid grid-cols-2 md:grid-cols-1 col-span-2 gap-4 '>
            <div v-for="(day, index) in dayForecast" :key="index" class="border p-5 flex gap-4 items-center justify-between" :class="{'col-span-2': index === dayForecast.length - 1}">
                <div class="day-data">
                    <p>{{ day[0].dt_txt.split(" ")[0] }}</p>
                    <p class="font-bold text-2xl">
                        {{ new Date(day[0].dt_txt).toLocaleDateString('en-US', { weekday: 'long' }) }}
                    </p>
                    <p>{{ day[0].weather[0].description }}</p>
                </div>

                <div class="day-temp my-auto text-lg items-start flex-1">
                    <p>Feels Like {{ Math.round(day[0].main.feels_like) }}°</p>
                </div>

                <div id="icon">
                    <img :src="`http://openweathermap.org/img/wn/${day[0].weather[0].icon}.png`" alt="">
                </div>
            </div>
        </section>
    </main>
</template>


