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
				<div class="header">注册邮箱重置密码</div>
				<div class="desc">莞工在校生、教职工等注册时已填写邮箱的用户，可在此重置密码<br/>系统会发送一封重置密码邮件到您的注册邮箱，点击其中链接则可重置密码
				</div>
			</div>
			<el-form :model="form" :rules="rules" ref="form" label-width="100px" label-position="left" class="forgetForm">
				<el-form-item label="用户名" prop="username">
					<el-input type="text" v-model="form.username" placeholder="2~12位字符" auto-complete="off" style="width: 220px;"></el-input>
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
	
	export default{
		data(){
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
			return{
				form: {
					username: ''
				},
				rules: {
					username: [
						{ required: true,message: '请输入用户名',trigger: 'blur' },
						{ min: 2, max:12, message: '长度在2~12个字符', trigger: 'blur' },
						{ validator: validateUsername, trigger: "blur"}
					]
				}
			}
		},
		methods: {
			// 提交用户名
			submitForm(formName) {
				// 区分 this
				const self = this;
				self.$refs[formName].validate((valid) => {
				    if (valid) {
				        // 向后端传输数据（application/json）
				        let data = {
							"username": self.form.username
				        };
				        self.axios.post('http://vt.com/php/index.php/admin/users/findPwd', data).then(function (res) {
				            console.log(res)
				            if (res.status === 200 && res.data['error'] === '') {
								// 跳转路由到 确认邮箱
								setTimeout(() => {
									router.push({
										// 此处的 name 必须与 route.js 的相同
										name: 'CFirmemail',
										params: {
											username: res.data['data']['username'],
											email: res.data['data']['email']
										}
									})
								}, 1500)
				            } else{
				            	self.handleError(res.data);
				            }
				        }).catch(function (error) {
				            if(error.status === 504){
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

	/* form 表单*/
	.main-content{
	    position: relative;
	    top: -100px;
	    width: 800px;
	    margin: 0 auto;
	    background: white;
	    padding: 30px 50px;
	    /*box-shadow<length> <length> <length>? <length>? || <color>：
	    阴影水平偏移值（可取正负值）；阴影垂直偏移值（可取正负值）；阴影模糊值；阴影颜色 */
	    /*rgba(0, 0, 0, 0)*/
	    /*前三个值（红绿蓝）的范围为0到255之间的整数或者0%到100%之间的百分数。这些值描述了红绿蓝三原色在预期色彩中的量。*/
	    /*第四个值，alpha值，制订了色彩的透明度/不透明度，它的范围为0.0到1.0之间，0.5为半透明。*/
	    box-shadow: 0 0 10px rgba(13, 5, 9, 0.27);
	    -webkit-box-shadow: 0 0 10px rgba(13, 5, 9, 0.27);
	    -moz-box-shadow: 0 0 10px rgba(13, 5, 9, 0.27);
	}
	.forgetForm{
		width: 800px;
		margin: 0 auto;
	}
	.title-desc{
		font-size: 13px;
		color: #959595;
	}
	.title-desc .header{
		margin-bottom: 10px;
		color: #666666;
		font-size: 14px;
	}
	.title-desc .desc{
		line-height: 20px;
		margin-bottom: 100px;
	}
</style>
