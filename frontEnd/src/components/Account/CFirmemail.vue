<template>
	<div>
		<div class="top-bg">
			<div class="header-title">
				<span class="headZh">莞工娜娜</span>
				<span class="dName">nana.dgut.edu.cn</span>
			</div>
		</div>
		<div class="main-content">
			<div class="title-desc">
				<div class="header">确认注册邮箱</div>
				<div class="desc">尊敬的{{ userName }}，您正在找回密码，请确认注册时填写的邮箱地址：<br />
					您注册时填写的邮箱地址为:{{ emailName }}
				</div>
			</div>
			<el-form :model="form" :rules="rules" ref="form" label-width="100px" label-position="left" class="forgetForm">
				<el-form-item label="邮箱" prop="email">
					<el-input type="text" v-model="form.email" auto-complete="off" style="width: 45%;"></el-input>
				</el-form-item>
				<el-form-item style="margin-top: 100px;">
					<el-button type="primary" @click.native.prevent="submitForm('form')" style="width: 20%;">确定</el-button>
					<el-button @click="resetForm('form')" style="width: 20%;">重置</el-button>
				</el-form-item>
			</el-form>
		</div>
	</div>
</template>

<script>
	export default {


		data() {
			// 邮箱的表单验证
			var validateEmail = (rule, value, callback) => {
				// 只需匹配邮箱的名称，无需匹配域名（邮箱正则表达式：^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$）
				var re = /^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/;
				if (value === '') {
					callback(new Error("请输入邮箱"));
				} else if (!re.test(value)) {
					callback(new Error("请输入正确的邮箱名称"));
				} else {
					callback();
				}
			};
			return {
				userName: '',
				emailName: '',

				form: {
					email: ''
				},

				rules: {
					email: [
						{ require: true, message: '请输入邮箱', trigger: 'blur' },
						{ validator: validateEmail,trigger: 'blur' }
					],
				}
			}
		},
		created() {
			this.getParams();
		},
		// watch() {
		// 	'$route':'getParams'
		// },
		methods: {
			// 获取参数
			getParams() {
				// params 传参
				let self = this;
				self.userName = self.$route.params.username;
				self.emailName = self.$route.params.email
				// 如果是 query 传参，那就是this.$route.query.email
			},
			// 提交邮箱地址
			submitForm(formName) {
				// 区分 this
				const self = this;
				self.$refs[formName].validate((valid) => {
					if (valid) {
						// 向后端传输数据（application/json）
						let data = {
							"email": self.form.email
						};
						self.axios.post('http://vt.com/php/index.php/admin/users/findEmail', data).then(function(res) {
							console.log(res)
							if (res.status === 200 && res.data['error'] === '') {
								// 确认已发送至邮箱
								_g.toastMsg('success', '用于找回密码的链接已经发送到邮箱，请登录邮箱继续找回密码流程');
							} else if(res.data['error'] === '邮箱错误') {
								_g.toastMsg('error', '邮箱错误');
							} else if(res.data['error'] === '发送失败') {
								_g.toastMsg('error', '发送失败');
							}
						}).catch(function(error) {
							if (error.status === 504) {
								bus.$message({
									message: '请求超时，请检查网络',
									type: 'warning'
								})
							}
						})
					} else {
						// console.log('error submit!!');
						return false
					}
				});
			},

			// 重置信息
			resetForm(formName) {
				this.$refs[formName].resetFields();
			},
		}
	}
</script>

<style>
	/*顶部样式*/
	/*top-head.css*/
</style>
