<template>
    <div>
        <div class="top-bg">
            <div class="header-title">
                <span class="headZh">莞工娜娜</span>
                <span class="dName">nana.dgut.edu.cn</span>
            </div>
        </div>

        <div class="main-content" v-show="showItem" style="min-height:300px;">
            <div class="msg">链接已失效</div>
        </div>
        <div class="main-content" v-show="!showItem">
            <div class="title-desc">
                <div class="header">重置密码</div>
                <div class="desc">
                    请填写您的新密码以及进行确认：
                </div>
            </div>
            <el-form :model="form" :rules="rules" ref="form" label-width="100px" label-position="left" class="forgetForm">
                <el-form-item label="密码" prop="password">
                    <el-input type="password" v-model="form.password" placeholder="8~16位大小写字母和数字的组合，不能使用特殊字符" auto-complete="off" class="input-w"></el-input>
                </el-form-item>
                <el-form-item label="确认密码" prop="checkPassword">
                    <el-input type="password" v-model="form.checkPassword" auto-complete="off" class="input-w"></el-input>
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
        name: "Resetpwd",
        data(){
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
            return{
                username: '',
                
                // 是否显示
                showItem: '',
                
                form: {
                    password: '',
                    checkPassword: ''
                },

                rules: {
                    password: [
                        { require: true, message: '请输入密码', trigger: 'blur' },
                        { min: 8, max:16, message: '长度在8~16个字符', trigger: 'blur' },
                        { validator: validatePass, trigger: 'blur'}
                    ],
                    checkPassword: [
                        { require: true, message: '请输入确认密码', trigger: 'blur' },
                        { validator: validatePass2, trigger: 'blur' }
                    ],
                }
            }
        },
        created() {
            this.getParams()
        },
        methods: {
            // 获取参数
            getParams(){
                // params 传参
                let self = this;
                self.username = self.$route.params.username;
                if (self.username === '链接已失效') {
                    self.showItem = true;
                }
            },
            // 提交重置密码数据到后端
            submitForm(formName) {
                // 区分 this
                const self = this;
                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        // 向后端传输数据（application/json）
                        let data = {
                            'username': self.username,
                            "password": self.form.password
                        };
                        self.axios.post('http://vt.com/php/index.php/admin/users/resetPwd',data).then(function (res) {
                            console.log(res);
                            if (res.status === 200 && res.data['error'] === '') {
                                _g.toastMsg('success', '重置密码成功');
                                setTimeout(() => {
                                    router.replace('/')
                                }, 1500)
                            } else{
                                _g.toastMsg('success', '重置密码失败');
                            }
                        }).catch(function (error) {
                            if(error.status === 504){
                                bus.$message({
                                    message: '请求超时，请检查网络',
                                    type: 'warning'
                                })
                            }
                            _g.toastMsg('error', '重置密码失败')
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
    /*链接已失效样式*/
    .msg{
        width: 180px;
        height: 100px;
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        margin: auto;
        text-align: center;
        font-size: 24px;
        font-weight: bold;
    }
</style>