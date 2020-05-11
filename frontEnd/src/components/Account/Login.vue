<template>
	<!-- 轮播图 -->

	<div>
		<swiper :options="swiperOption" ref="mySwiper" style="margin: 0 auto;width: 80%;height: 80%;">
			<!-- 添加的图片 -->
			<!-- img 标签要加 style 的 width 和 height ， 一般为100%， 不然显示有bug -->
			<!-- 需要跳转，则在 img 标签外添加 a 标签-->
			<swiper-slide><img class="banner-img" src="../../assets/images/05.jpg" style="width: inherit;height: 450px;" />
			</swiper-slide>
			<swiper-slide><img class="banner-img" src="../../assets/images/06.jpg" style="width: inherit;height: 450px;" />
			</swiper-slide>
			<swiper-slide><img class="banner-img" src="../../assets/images/07.jpg" style="width: inherit;height: 450px;" />
			</swiper-slide>
			<!--<swiper-slide><img class="banner-img" src="../../assets/images/bg1.jpg"/> </swiper-slide>-->
			<!-- Optional controls -->
			<!-- 分页器 -->
			<div class="swiper-pagination" slot="pagination"></div>
			<div class="swiper-button-prev" slot="button-prev"></div>
			<div class="swiper-button-next" slot="button-next"></div>
		</swiper>
		<el-form :model="form" :rules="rules2" ref="form" label-position="left" label-width="0px" class="loginform">
			<!--<h3 class="title">{{systemName}}</h3>-->

			<el-form-item prop="username" class="form-item">
				<el-input type="text" v-model="form.username" auto-complete="off" placeholder="用户名 / username"></el-input>
			</el-form-item>
			<el-form-item prop="password" class="form-item">
				<el-input type="password" v-model="form.password" auto-complete="off" placeholder="密码 / password"></el-input>
			</el-form-item>
			<el-form-item v-show="requireVerify" prop="verifyCode" class="form-item">
				<el-input type="text" v-model="form.verifyCode" auto-complete="off" placeholder="验证码 / captcha"></el-input>
				<img :src="verifyUrl" @click="refreshVerify()" class="verify-pos" />
			</el-form-item>
			<el-checkbox v-model="checked" style="margin:0 0 40px 110px;" class="checkbox-pleft"><span class="check-style">一周内自动登录</span></el-checkbox>
			<div class="others">
				<span class="separator"></span>
				<!-- <el-link class="link" :underline="false">游客</el-link> -->
				<el-link @click="goForgetpwd()" :underline="false" class="link">忘记密码</el-link>
				<!-- <a @click="goForgetpwd()">忘记密码</a> -->
			</div>
			<el-form-item class="button-style">
			<!--
			@click.native.prevent
			1.在封装好的组件上使用，所以要加上.native才能click
			2.prevent就相当于..event.preventDefault()
			根据Vue2.0官方文档关于父子组件通讯的原则，父组件通过prop传递数据给子组件，子组件触发事件给父组件。
			但父组件想在子组件上监听自己的click的话，需要加上native修饰符，故写法就像上面这样。-->
			<el-button type="primary" style="width:15%;" @click.native.prevent="handleSubmit2('form')">登录</el-button>
			<el-button style="width:15%;" @click="goSignup()">注册</el-button>
			<!-- 就是显示交互的一种方式 -->
			<!--使用v-loading在接口为请求到数据之前，显示加载中，直到请求到数据后消失。
			//全局loading
			<template>
			  <div v-loading="loading"> </div>
			</template>
			在data 中定义初始化， loading: false，同时在mounted()中将 this.loading设置为true,再去请求接口
			在接口的回调函数中，将 this.loading 设为false，到达效果。-->
			</el-form-item>
		</el-form>
	</div>
</template>

<script>
	// import http from '../../assets/js/http'

	// 轮播图插件
	import { swiper,swiperSlide } from 'vue-awesome-swiper'
	import 'swiper/dist/css/swiper.css'


	export default {
		// 声明组件的使用
		components: {
			swiper,
			swiperSlide
		},
		data() {
			// 用户名的表单验证
			var validateUsername = (rule, value, callback) => {
			    // 下面正则表达式可以匹配所有特殊字符（所有键盘上可见的非字母和数字的符号）
			    var re = /[`~!@#$%^&*()_\-+=<>?:"{}|,.\/;'\\[\]·~！@#￥%……&*（）——\-+={}|《》？：“”【】、；‘’，。、]/im;
			    if (value === "") {
			        callback(new Error("请输入用户名"));
			    } else if (re.test(value)) {
			        callback(new Error("请输入正确的用户名"));
			    } else {
			        callback();
			    }
			};
			return {
				// 轮播图选项
				swiperOption: {
					// 分页器
					pagination: {
						el: '.swiper-pagination',
						// 点击切换图片
						clickable: true,
					},
					// 设置显示容器同时显示的数量
					slidesPerView: 1,
					spaceBetween: 30,
					// centeredSlides: false,
					onSlideChangeEnd: swiper => {
						//这个位置放swiper的回调方法
						this.page = swiper.realIndex + 1;
						this.index = swiper.realIndex;
					},
					// 前进后退按钮
					navigation: {
						nextEl: '.swiper-button-next',
						prevEl: '.swiper-button-prev',
					},
					//自动播放
					autoplay: {
						delay: 3000,
						disableOnInteraction: false
					},
					//循环轮播
					loop: true
				},

				// title: '',
				// systemName: '',
				// 点击 登录 按钮时的样式设定
				// loading: false,

				// 表单数据
				form: {
					username: '',
					password: '',
					verifyCode: ''
				},

				// 验证码项是否显示
				requireVerify: true,
				verifyUrl: '',
				// 获取图片地址
				// window.HOST = 'http://vt.com/php/index.php/'
				// 调用后端 getVerify 方法即可生成验证码图片地址
				verifyImg: window.HOST + 'admin/users/getVerify',

				// 表单字段验证规则
				rules2: {
					// 验证内容是：必填，失去焦点时验证，如果为空，提示信息为 '请输入用户名称'，以下类推
					username: [
						{ required: true,message: '请输入用户名',trigger: 'blur' },
						{ min: 2, max:12, message: '长度在2~12个字符', trigger: 'blur' },
						{ validator: validateUsername, trigger: "blur"}
					],
					password: [
						{ required: true,message: '请输入密码',trigger: 'blur' },
						{ min: 8, max:16, message: '长度在8~16个字符', trigger: 'blur' }
					],
					verifyCode: [{
						required: true,
						message: '请输入验证码',
						trigger: 'blur'
					}]
				},
				// 记住密码单选框，一开始未选
				checked: false,
				// 一周内自动登录
				isRemember: ''
			}
		},
		// 定义 swiper 对象
		computed: {
			swiper() {
				// 与 <swiper> 标签的属性 ref 相对应
				return this.$refs.mySwiper.swiper;
			}
		},
		methods: {
			// 重新获取验证码图片
			refreshVerify() {
				// 每次刷新均重置为空
				this.verifyUrl = ''

				setTimeout(() => {
					// moment.js 为轻量级的一个js日期处理类库
					// moment().unix() // 获取时间戳（以秒为单位），返回值为数值型
					this.verifyUrl = this.verifyImg + '?v=' + moment().unix()
				}, 300)
			},
			// 登录
			handleSubmit2(formName) {
				// 区分this
				const self = this;
				// 若 loading 为 false ，按钮不做变化
				// if (this.loading) return

				// validate.js 表单验证的一种方式（JQ）
				self.$refs[formName].validate((valid) => {
					if (valid) {
						self.loading = !self.loading
						// let 声明一个 data 变量，进行表单数据的存储
						// 记住密码（自动登录）标识
						if (self.checked) {
							self.isRemember = 1
						} else {
							self.isRemember = 0
						}
						let data = {
							// 存储登录表单数据
							"username": self.form.username,
							"password": self.form.password,
							"verifyCode": self.form.verifyCode,
							"isRemember": self.isRemember
						};

						// 向后端传输数据
						self.axios.post('http://vt.com/php/index.php/admin/users/login', data).then(function(res) {
							console.log(res)
							if (res.status === 200 && res.data['error'] === '') {
								_g.toastMsg('success', '登录成功');
								// 刷新验证码
								self.refreshVerify();
								// 存储登录信息到 Cookie
								if (self.checked) {
									Cookies.set('rememberPwd', 1, { expires: 7 });
								}
								// 存储数据到 localStorage
								// Lockr 插件无法使用，故用原生 window.localStorage 方法
								self.setLocalData(res.data['data']);
								setTimeout(() => {
									router.replace('/Home')
								}, 1000)
							} else {
								// 刷新验证码
								self.refreshVerify();
								self.handleError(res.data);
							}
						}).catch(function(error) {
							if(error.status === 504){
								bus.$message({
									message: '请求超时，请检查网络',
									type: 'warning'
								})
							}
						})
					} else {
						return false
					}
				})
			},

			// 检查是否记住密码(自动登录)
			checkIsRememberPwd() {
				// 区分 this ，very important.
				const self = this;
				if (Cookies.get('rememberPwd')) {
					// console.log(Cookies.get('rememberPwd'));
					let storage = window.localStorage;
					let data = {
						rememberKey: storage.getItem('rememberKey')
					};
					self.axios.post('http://vt.com/php/index.php/admin/users/relogin', data).then(function(res) {
						console.log(res);
						if (res.status === 200 && res.data['error'] === '') {
							_g.toastMsg('success', '登录成功');
							// 重新写入localStorage
							self.setLocalData(res.data['data']);
							setTimeout(() => {
								router.replace("/home")
							},1000)
						}else{
							_g.toastMsg('error', '登录失败');
						}
					}).catch(function(error) {
						if(error.status === 504){
							bus.$message({
								message: '请求超时，请检查网络',
								type: 'warning'
							})
						}
					})
				}
			},
			// 保存登录信息在 localstorage
			setLocalData(data) {
				let storage = window.localStorage;
				// 权限认证
				storage.setItem('authKey', data['authKey']); 
				// 记住密码的加密字符串
				storage.setItem('rememberKey', data['rememberKey']); 
				Lockr.set('userInfo',data['userInfo']);
				// 用户sessionid
				storage.setItem('sessionId', data['sessionId']); 
				// 添加 authKey 到 axios 头部
				window.axios.defaults.headers.authKey = storage.getItem('authKey');
			},
			// 错误处理
			handleError(data) {
				if (data['error']) {
					switch (data['error']) {
						case '用户名不存在':
							_g.toastMsg('error', '用户名不存在');
							break;
						case '密码错误':
							_g.toastMsg('error', '密码错误');
							break;
						case '账号已被禁用':
							_g.toastMsg('error', '账号已被禁用');
							break;
						case '验证码错误':
							_g.toastMsg('error', '验证码错误');
							break;
						default:
							_g.toastMsg('error', 'default error');
							break;
					}
				}
			},
			// 跳转注册页面
			goSignup() {
				this.$router.push({
					name: 'Signup',
					params: {}
				})
			},
			// 跳转忘记密码页面
			goForgetpwd(){
				this.$router.push({
					name: 'Forgetpwd',
					params: {}
				})
			}
		},

		created() {
			// 创建完成后检查是否“记住密码（自动登录）”
			this.checkIsRememberPwd();

			this.verifyUrl = this.verifyImg
		},
		mounted() {
			// 轮播图
			this.swiper.slideTo(0, 0, false);

			window.addEventListener('keyup', (e) => {
				// 监听 enter 键
				if (e.keyCode === 13) {
					this.handleSubmit2('form')
				}
			})
		},
		// mixins: [http]
	}
</script>


<style>
	.loginform {
		width: 1000px;
		margin: 0 auto;
		text-align: center;
		margin-top: 20px;
		/*padding: 35px 35px 15px 35px;*/
	}

	/* .form-item:first-of-type{
		margin-left: 20px;
	} */
	.form-item {
		width: 150px;
		display: inline-block;
		margin-left: 10px;
	}

	.verify-pos {
		position: absolute;
		left: 152px;
		top: 2px;
	}

	.check-style {
		color: #959595;
		font-size: 12px;
		/* padding-left: -10px; */
	}
	.checkbox-pleft .el-checkbox__label{
		padding-left: 5px;
	}

	/*修改复选框（记住密码）样式*/
	.el-checkbox__input.is-checked .el-checkbox__inner {
		background-color: white;
		border-color: #dcdcdc;
	}

	.el-checkbox__inner {
		width: 18px;
		height: 18px;
	}

	.el-checkbox__inner::after {
		box-sizing: content-box;
		content: "";
		border: 2px solid #0190fe;
		border-left: 0;
		border-top: 0;
		height: 8px;
		left: 5px;
		position: absolute;
		top: 1px;
		-ms-transform: rotate(45deg) scaleY(0);
		transform: rotate(45deg) scaleY(0);
		width: 4px;
		transition: transform .15s cubic-bezier(.71, -.46, .88, .6) .05s;
		-ms-transform-origin: center;
		transform-origin: center;
	}
	
	.button-style{
		text-align: center;
	}

	.others{
		display: inline-block;
		font-size: 12px;
		color: #959595;
	}
	.others .separator{
		display: inline-block;
		position: relative;
		bottom: -15px;
		width: 1px;
		height: 40px;
		margin: 0 15px;
		border-left: 1px solid #DDDDDD;
	}
	.others a{
		display: inline-block;
		color: #959595;
	}
	.others a:nth-of-type(2){
		margin-left: 8px;
	}
	.others .link{
		font-size: 12px;
		color: #959595;
		vertical-align: unset !important;
	}
	
</style>
