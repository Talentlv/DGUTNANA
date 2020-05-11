import 'babel-polyfill'
import Vue from 'vue'
import VueAwesomeSwiper from 'vue-awesome-swiper'

// require styles
import 'swiper/dist/css/swiper.css'


import App from './App'
import axios from 'axios'
import Qs from 'query-string'
// import VueAxios from 'vue-axios'
import Lockr from 'lockr'
import Cookies from 'js-cookie'
import _ from 'lodash'
import moment from 'moment'
import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'
import routes from './routes'
import VueRouter from 'vue-router'
import store from './vuex/store'
// 过滤器
import filter from './assets/js/filter'
import _g from './assets/js/global'
  // 页面加载进度条
import NProgress from 'nprogress'   
//import $ from 'jquery'
import 'nprogress/nprogress.css'
import 'assets/css/global.css'
import 'assets/css/top-head.css'

axios.defaults.baseURL = 'http://vt.com/php/index.php/';
axios.defaults.timeout = 1000 * 15;
axios.defaults.headers.authKey = Lockr.get('authKey');
axios.defaults.headers.sessionId = Lockr.get('sessionId');
axios.defaults.headers['Content-Type'] = 'application/json';	// 以 json 格式传输数据

// 在入口main.js中导入axios 并将axios写入vue的原型
Vue.prototype.axios = axios;
Vue.prototype.qs = Qs;


const router = new VueRouter({
  mode: 'history',
  base: __dirname,
  // 路由表
  routes
});

router.beforeEach((to, from, next) => {

  store.dispatch('showLoading', true)

  // 页面加载进度条
  NProgress.start()
  // 路由发生改变修改页面的title
  if(to.meta.title) {
    document.title = to.meta.title
  }
  next()
});

router.afterEach(transition => {
  NProgress.done()
});

Vue.use(ElementUI);
Vue.use(VueRouter);
Vue.use(VueAwesomeSwiper, /* { default global options } */);
// Vue.use(VueAxios,axios)

window.router = router;
window.store = store;
window.HOST = 'http://vt.com/php/index.php/';
window.axios = axios;
window._ = _;
window.moment = moment;
// Lockr.prefix = Lockr;
window.Lockr = Lockr;
window.Cookies = Cookies;
window._g = _g;
// window.pageSize = 15;

const bus = new Vue();
window.bus = bus;

new Vue({
  el: '#app',
  template: '<App/>',
  filters: filter,
  router,
  store,
  components: { App }
// render: h => h(Login)
}).$mount('#app');
