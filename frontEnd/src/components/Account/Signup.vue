<template>
    <div>
        <div class="top-bg">
            <div class="header-title">
                <span class="headZh">莞工娜娜</span>
                <span class="dName">nana.dgut.edu.cn</span>
            </div>
        </div>
        <div class="main-content">
            <el-form :model="form" :rules="rules" ref="form" label-width="100px" label-position="left" class="registerForm">
                <el-form-item label="学号" prop="num">
                    <el-input v-model="form.num" placeholder="学号 / 工号" auto-complete="off" class="input-w"></el-input>
                </el-form-item>
                <el-form-item label="用户名" prop="username" class="inline">
                    <el-input type="text" v-model="form.username" placeholder="2~12位字符" auto-complete="off" class="input-w"></el-input>
                    <span>一经注册无法修改</span>
                </el-form-item>
                <el-form-item label="昵称" prop="nickname" class="inline">
                    <el-input type="text" v-model="form.nickname" placeholder="例如，小才" auto-complete="off" class="input-w"></el-input>
                    <span>注册后可随时修改</span>
                </el-form-item>
                <el-form-item label="密码" prop="password">
                    <el-input type="password" v-model="form.password" placeholder="8~16位大小写字母和数字的组合，不能使用特殊字符" auto-complete="off" class="input-w"></el-input>
                </el-form-item>
                <el-form-item label="确认密码" prop="checkPassword">
                    <el-input type="password" v-model="form.checkPassword" auto-complete="off" class="input-w"></el-input>
                </el-form-item>
                <el-form-item label="邮箱" prop="email" class="email-style">
                    <el-input type="text" v-model="form.email" auto-complete="off" placeholder="目前仅支持QQ、莞工邮箱" class="input-w" style="width: 102%;"></el-input>
                </el-form-item>
                <el-form-item label="" prop="options" class="email-style email-options">
                    <!-- <el-select>的使用需要升级 element-ui 1.3以上版本才能正常使用 -->
                    <el-select v-model="form.options" value="@qq.com" placeholder="@qq.com" style="width: 70%;">
                        <el-option label="@qq.com" value="@qq.com"></el-option>
                        <el-option label="@dgut.edu.cn" value="@dgut.edu.cn"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="性别" prop="gender">
                    <el-radio-group v-model="form.gender" class="radio-style">
                        <el-radio label="男"></el-radio>
                        <el-radio label="女"></el-radio>
                    </el-radio-group>
                </el-form-item>
                <el-form-item label="生日" prop="birthday">
                    <el-date-picker v-model="form.birthday" placeholder="请选择日期" default-value="1997-3-17" format="yyyy年MM月dd日" value-format="yyyy-MM-dd"></el-date-picker>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click.native.prevent="submitForm('form')" style="width: 20%;">注册</el-button>
                    <el-button @click="resetForm('form')" style="width: 20%;">重置</el-button>
                </el-form-item>
            </el-form>
            <div class="signContainer-tip">
                <div class="">注册即代表同意
                    <a href="#">《莞工娜娜协议》</a>
                    <a href="#">《隐私保护指引》</a>
                </div>
            </div>
        </div>
    </div>

</template>

<script>

    // import http from '../../assets/js/http'
    // import qs from 'query-string';
    export default {
        data(){
            // 学号的表单验证
            var validateNum = (rule, value, callback) => {
                var re = /^\d{12}$/;
                if (value === "") {
                    callback(new Error("请输入学号 / 工号"));
                }else if (!re.test(value)) {
                    callback(new Error("学号/工号为12位非负整数"));
                }else {
                    callback();
                }
            };
            // 用户名的表单验证
            var validateUsername = (rule, value, callback) => {
                // 下面正则表达式可以匹配所有特殊字符（所有键盘上可见的非字母和数字的符号）
                var re = /[`~!@#$%^&*()_\-+=<>?:"{}|,.\/;'\\[\]·~！@#￥%……&*（）——\-+={}|《》？：“”【】、；‘’，。、]/im;
                if (value === "") {
                    callback(new Error("请输入用户名"));
                } else if (re.test(value)) {
                    callback(new Error("用户名不能含有特殊字符"));
                } else {
                    callback();
                }
            };
            // 昵称的表单验证
            var validateNickname = (rule, value, callback) => {
                // 下面正则表达式可以匹配所有特殊字符（所有键盘上可见的非字母和数字的符号）
                var re = /[`~!@#$%^&*()_\-+=<>?:"{}|,.\/;'\\[\]·~！@#￥%……&*（）——\-+={}|《》？：“”【】、；‘’，。、]/im;
                if (value === "") {
                    callback(new Error("请输入昵称"));
                } else if (re.test(value)) {
                    callback(new Error("昵称不能含有特殊字符"));
                } else {
                    callback();
                }
            };
            // 密码的表单验证
            var validatePass = (rule, value, callback) => {
                // ^[a-zA-Z0-9_]{7,15}$ 匹配由数字、26个英文字母或者下划线组成的字符串
                // ^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,10}$ 必须包含大小写字母和数字的组合，不能使用特殊字符，长度在8-10之间
                var re = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{7,17}$/;
                if (value === '') {
                    callback(new Error("请输入密码"));
                }else if (!re.test(value)) {
                    callback(new Error("密码必须包含大小写字母和数字的组合，不能使用特殊字符"));
                } else {
                    if (this.form.checkPassword !== ''){
                        // 检验 checkPassword
                        this.$refs.form.validateField('checkPassword') ;
                    }
                    callback();
                }
            };
            // 确认密码的表单验证
            var validatePass2 = (rule, value, callback) => {
                if (value === '') {
                    callback(new Error("请再次输入密码"));
                }else if ( value !== this.form.password){
                    callback(new Error("两次输入密码不一致"));
                }else {
                    callback();
                }
            };
            // 邮箱的表单验证
            var validateEmail = (rule, value, callback) => {
                // 只需匹配邮箱的名称，无需匹配域名（邮箱正则表达式：^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$）
                var re = /^[a-zA-Z0-9_-]+$/;
                if (value === '') {
                    callback(new Error("请输入邮箱"));
                }else if ( !re.test(value) ){
                    callback(new Error("请输入正确的邮箱名称"));
                }else {
                    callback();
                }
            };
            // 生日的表单验证
            var validateBirthday = (rule, value, callback) => {
                if (value === '') {
                    callback(new Error("请输入生日"));
                }else {
                    callback();
                }
            };


            return{
                form: {
                    num: '',
                    username: '',
                    nickname: '',
                    password: '',
                    checkPassword: '',
                    email: '',
                    options: '@qq.com',
                    gender: '男',
                    birthday: ''
                },
                rules: {
                    num: [
                        { require: true, message: '请输入学号', trigger: 'blur' },
                        { validator: validateNum, trigger: 'blur' }
                    ],
                    username: [
                        { require: true, message: '请输入用户名', trigger: 'blur' },
                        { min: 2, max:12, message: '长度在2~12个字符', trigger: 'blur' },
                        { validator: validateUsername, trigger: "blur"}
                    ],
                    nickname: [
                        { require: true, message: '请输入昵称', trigger: 'blur' },
                        { min: 1, max:10, message: '长度在1~12个字符', trigger: 'blur' },
                        { validator: validateNickname, trigger: 'blur'}
                    ],
                    password: [
                        { require: true, message: '请输入密码', trigger: 'blur' },
                        { min: 8, max:16, message: '长度在8~16个字符', trigger: 'blur' },
                        { validator: validatePass, trigger: 'blur'}
                    ],
                    checkPassword: [
                        { require: true, message: '请输入确认密码', trigger: 'blur' },
                        { validator: validatePass2, trigger: 'blur' }
                    ],
                    email: [
                        { require: true, message: '请输入邮箱', trigger: 'blur' },
                        { validator: validateEmail, trigger: 'blur' }
                    ],
                    birthday: [
                        { require: true, message: '请输入日期', trigger: 'change'},
                        { validator: validateBirthday, trigger: 'change'}
                    ]
                },
            }
        },
        methods: {
            // 提交用户注册数据到后端
            submitForm(formName) {
				// 区分 this
				const self = this;
                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        // 向后端传输数据（application/json）
                        let data = {
                                "num": self.form.num,
                                "username": self.form.username,
                                "nickname": self.form.nickname,
                                "password": self.form.password,
                                "email": self.form.email + self.form.options,
                                "gender": self.form.gender,
                                "birthday": self.form.birthday
                        };
                        self.axios.post('http://vt.com/php/index.php/admin/users/register',data).then(function (res) {
                            console.log(res)
                            if (res.status === 200 && res.data['error'] === '') {
                            	_g.toastMsg('success', '注册成功');
								setTimeout(() => {
									router.replace('/')
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
                            _g.toastMsg('error', '注册失败')
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
			
			handleError(data){
				if (data['error']) {
					switch (data['error']) {
						case '该用户名已被占用':
							_g.toastMsg('error', '注册失败，该用户名已被占用');
							break;
						case '该学号已经注册，请登录':
							_g.toastMsg('error', '该学号已经注册，请登录');
							setTimeout(() => {
							    router.replace('/')
							},1500)
							break;
						default:
							_g.toastMsg('error', 'default error');
							break;
					}
				}
			}
        },
        mounted() {
            window.addEventListener('keyup', (e) => {
                // 监听 enter 键
                if (e.keyCode === 13) {
                    this.submitForm('form');
                }
            })
        }
        // // 导入 http.js 必须有以下
        // mixins: [http]
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
    .registerForm{
        width: 800px;
        margin: 0 auto;
        /*margin-top: 100px;*/
    }
    .inline span{
        display: inline-block;
        color: #959595;
        font-size: 14px;
        margin-left: 8px;
    }
    /* 小红星* */
    .el-form-item__label:before {
        content: '*';
        color: #F56C6C;
        margin-right: 4px;
    }

    .input-w{
        width: 50%;
    }
    /* 重置 input 框的*/
    .input-w .el-input__inner{
        font-size: 13px;
    }
    /* 单选框邮箱样式 */
    .email-style{
        display: inline-block !important;
    }
    .email-options .el-form-item__content{
        margin-left: 5px !important;
    }
    .radio-style .el-radio__input.is-checked .el-radio__inner {
         border-color: #DCDFE6;
         background: #FFF;
    }
    .radio-style .el-radio__inner::after{
        width: 6px;
        height: 6px;
        background-color: #409EFF;
    }
    .radio-style .el-radio__input.is-checked+.el-radio__label{
        color: #606266;
    }
    .radio-style .el-radio__label{
        padding-left: 8px;
    }

    /* 注册须知 */
    .signContainer-tip{
        font-size: 14px;
        color: #959595;
    }
    .signContainer-tip a{
        display: inline-block;
        color: #00BFFF;
    }
</style>