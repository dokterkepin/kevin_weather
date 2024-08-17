<script setup >
import {ref, onMounted, } from 'vue';
import axios from 'axios';
import { MapboxSearchBox } from '@mapbox/search-js-web'

const lat = ref(22.62);
const lon = ref(120.312);
const cityName = ref("Kaohsiung")

const dayForecast = ref([]);
const weather = ref(null);


const cityDayTime = ref('');
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
        weather.value = response.data;
        console.log(weather.value);
        timeZone(weather.value.dt);
    }catch (errors){
        console.log('Error Fetching Weather Data', errors);
    }finally {
        isLoading.value = false;
    }
};

const fetchDayForecast = async () => {
  isLoading.value = true;
  try{
      const response = await axios.get('/api/forecast', {
          params: {
              lat: lat.value,
              lon: lon.value
          }
      });
      for(let i = 5; i < response.data.list.length; i += 8){
          dayForecast.value.push(response.data.list.slice(i, i + 8));
      }
      console.log(response.data.list);
      console.log(dayForecast.value);
  }catch(errors){
      console.log('Error Fetching Weather Data', errors);
  }finally {
      isLoading.value = false;
  }
};

const timeZone = (dt) => {
    const unixTime = dt + weather.value.timezone;
    const dateObj = new Date(unixTime * 1000);
    const day = new Intl.DateTimeFormat('en-US', {weekday: 'long', timeZone: 'UTC'}).format(dateObj);
    const time = new Intl.DateTimeFormat('en-US',{hour: 'numeric', minute: 'numeric', timeZone: 'UTC'}).format(dateObj);

}

onMounted(() => {
    searchBox();
    fetchWeather();
    fetchDayForecast();
});

</script>

<template>
    <nav id='search-box-container' class='w-4/6 mx-auto mt-20 mb-10'>
        <h1 class='text-4xl text-center my-5 font-bold'>WELCOME HOME</h1>
    </nav>

    <main id='weather-container' class='w-5/6 m-auto grid grid-cols-2 gap-4 ' v-if='!isLoading'>
        <section id='todayData' class=' grid grid-cols-5 gap-4 col-span-2 ' >

            <div id='mainData' class='border p-5 col-span-2'>
                <p class='font-bold text-2xl'> {{cityName}}</p>
                <p>{{cityDayTime}}</p>
                <p>{{weather.weather[0].description}}</p>
            </div>

            <div id='tempData' class='border grid grid-cols-2 text-center p-5'>
                <p class='col-span-2 m-auto text-5xl'>{{Math.round(weather.main.temp)}}°</p>
                <p class=''>H: {{Math.round(weather.main.temp_max)}}°</p>
                <p class='text-left'>L: {{Math.round(weather.main.temp_min)}}°</p>
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


        <section id='forcastData' class='grid grid-cols-2  col-span-2 gap-4 '>
            <div id='day1' class='border p-5'>
                <p class='font-bold text-2xl'> {{timeZone(dayForecast[0][0].dt)}}</p>
                <p></p>
                <p></p>
            </div>

            <div id='day2' class='border p-5'>
                <p class='font-bold text-2xl'> {{cityName}}</p>
                <p></p>
                <p></p>
            </div>

            <div id='day3' class='border p-5'>
                <p class='font-bold text-2xl'> {{cityName}}</p>
                <p></p>
                <p></p>
            </div>

            <div id='day4' class='border p-5'>
                <p class='font-bold text-2xl'> {{cityName}}</p>
                <p></p>
                <p></p>
            </div>

            <div id='day5' class='border p-5'>
                <p class='font-bold text-2xl'> {{cityName}}</p>
                <p></p>
                <p></p>
            </div>
        </section>
    </main>
</template>


