<template>
    <div>
        <el-breadcrumb separator-class="el-icon-arrow-right">
            <el-breadcrumb-item :to="{ path: '/home' }">首页</el-breadcrumb-item>
            <el-breadcrumb-item>发表文章</el-breadcrumb-item>
        </el-breadcrumb>
        <!-- <div style="height: 30px;"></div> -->
        <el-form :model="form" :rules="rules" ref="form" label-width="120px" label-position="left">
            <el-form-item label="文章所属类型" prop="options">
                <el-cascader v-model="form.value" :options="options" :show-all-levels="true" @change="handleChange"></el-cascader>
            </el-form-item>
            <el-form-item label="文章标题" prop="title">
                <el-input v-model="form.title" placeholder="2-20个字符" auto-complete="off" style="width: 50%;" @change="handleChange"></el-input>
            </el-form-item>
        </el-form>
        <!-- 文章正文-->
        <quill-editor 
            v-model="content" 
            ref="myQuillEditor" 
            :options="editorOption" 
            @blur="onEditorBlur($event)" @focus="onEditorFocus($event)"
            @change="onEditorChange($event)">
        </quill-editor>
        <!-- 文件上传input 将它隐藏-->
        <el-upload  class="upload-demo" 
                    action="upload" 
                    :headers="{'Content-Type':'multipart/form-data'}"
                    :http-request="uploadFile"
                    :show-file-list="false"
                    :auto-upload="true"
                    :before-upload='beforeUpload' 
                    :on-success='upSuccess'
                    ref="upload"
                    style="display:none">
                    <!-- <video v-if="imageUrl.length > 1 && imgFlag == false"  controls="controls" :src="[qiniuUrl + '/' + imageUrl]" class="avatar"></video> -->
            <el-button size="small" type="primary" id="imgInput" v-loading.fullscreen.lock="fullscreenLoading" element-loading-text="插入中,请稍候">点击上传</el-button>
        </el-upload>
        <div class="limit"><span :class="[thenColor==true?'then':'now']">{{nowLength}}</span> / <span class="max">{{SurplusLength}}</span></div>
        <div class="btnG">
            <el-button type="primary" @click.native.prevent="submitForm('form')" style="width: 20%;">发送</el-button>
            <el-button @click="resetForm('form')" style="width: 20%;">重置</el-button>
        </div>
        
    </div>
</template>

<script>
    import { quillEditor } from 'vue-quill-editor'
    // import 'quill/dist/quill.core.css'
    import 'quill/dist/quill.snow.css'
    // import 'quill/dist/quill.bubble.css'
    import Quill from 'quill'; // 富文本基于quill


    export default {
        name: "Aticleadd",
        components: {
            quillEditor
        },
        data(){
            // 文章所属类型的表单验证
            var validateOptions = (rule, value, callback) => {
                if (value === "") {
                    callback(new Error("请选择文章类型"));
                }else {
                    callback();
                }
            };
            // 文章标题的表单验证
            var validateTitle = (rule, value, callback) => {
                if (value === "") {
                    callback(new Error("标题不能为空"));
                }else {
                    callback();
                }
            };
            return {
                options: [{
                        label: '莞工生活',
                        value: 1,
                        children: [{
                            label: '跳蚤市场',
                            value: 1
                        },{
                            label: '奇闻趣事',
                            value: 2
                        },{
                            label: '有些相遇',
                            value: 3
                        },{
                            label: '学习至上',
                            value: 4
                        },{
                            label: '健康运动',
                            value: 5
                        },{
                            label: '无法触碰',
                            value: 6
                        }]
                    },{
                        label: '莞工资讯',
                        value: 2,
                        children: [{
                            label: '莞工官方',
                            value: 7
                        },{
                            label: '文艺活动',
                            value: 8
                        },{
                            label: '讲座动态',
                            value: 9
                        },{
                            label: '复印打印',
                            value: 10
                        },{
                            label: '兼职钟点',
                            value: 11
                        },{
                            label: '社团资讯',
                            value: 12
                        },{
                            label: '失物招领',
                            value: 13
                        }]
                    },{
                        label: '莞工资源',
                        value: 3,
                        children: [{
                            label: '课堂课件',
                            value: 14
                        },{
                            label: '历年试卷',
                            value: 15
                        },{
                            label: '电影剧集',
                            value: 16
                        },{
                            label: '动画番剧',
                            value: 17
                        },{
                            label: '游戏天下',
                            value: 18
                        },{
                            label: '综艺节目',
                            value: 19
                        }]
                    }],
                form: {
                    // 用户名
                    username: Lockr.get('authKey'),
                    // 文章类型
                    value: [],
                    // 文章标题
                    title: '',
                    // 文章内容

                },
                rules: {
                    options: [
                        { require: true, message: '请选择文章类型', trigger: 'blur' },
                        { validator: validateOptions, trigger: "blur"}
                    ],
                    title: [
                        { require: true, message: '标题不能为空', trigger: 'blur' },
                        { min: 2, max:20, message: '长度在2~20个字符', trigger: 'blur' },
                        { validator: validateTitle, trigger: "blur"}
                    ],
                },
                //editor: null,   // 富文本编辑器对象
                textLength: 1,
                // nowColor: 'now',
                thenColor: false,
                content: '', 
                editorOption: { //  富文本编辑器配置
                    theme: 'snow',
                    placeholder: '请输入正文'
                },
                // addRange: [],
                // uploadData: {},
                photoUrl: '',         // 上传图片地址
                uploadType: '',    // 上传的文件类型（图片、视频）
                fullscreenLoading: false,

                $refs: {
                    myQuillEditor: HTMLInputElement,
                    imgInput: HTMLInputElement
                }
            }
        },
        computed: {
            nowLength(){
                if (this.textLength-1 <10001) {
                    return this.textLength-1;
                } else {
                    return 10000;
                }
            },
            SurplusLength() {   // 计算属性 获得当前输入字符长度
                // let num = 10000 - Number(this.content.length)
                // if (num > 0) {
                //     return num
                // } else {
                //     return 0
                // }
                return 10000;
            },
        },
        methods:{
            // getUsername(){
            //     const self = this;
            //     self.username = Lockr.get
            // },
            handleChange(value) {
                console.log(value);
            },
            // 准备富文本编辑器
            onEditorReady (editor) {},
            // 编辑器光标离开 将编辑器内容发射给父组件
            onEditorBlur(editor) {
                this.$emit('getValue', this.content)
            },

            // 编辑器获得光标
            onEditorFocus(editor) {
                editor.enable(true)   // 实现达到上限字符可删除
            },

            // 编辑器文本发生变化
            onEditorChange({ editor, html, text }) {
                let textLength = text.length
                this.textLength = textLength
                // console.log(textLength);
                // console.log(html);
                if (textLength > 10001) {
                    _g.toastMsg('error','最多输入10000个字符')
                    this.thenColor = true
                    editor.enable(false)
                }else{
                    this.thenColor = false
                }
                this.$emit('getValue', this.content)
                // console.log(this.content);
            },

            // 清除编辑器内容
            callMethod() {
                this.content = ''
            },
            // 富文本编辑器 点击插入图片或者视频
            // insertImgClick (e) {
            //     if (e.target.className.indexOf('el-icon-picture') != -1) {
            //         document.getElementById('insert_image').click();
            //     } else if (e.target.className.indexOf('el-icon-film') != -1) {
            //         document.getElementById('insert_video').click();
            //     }
            // },
            // 富文本编辑器 点击插入图片或者视频上传并预览
            uploadFile (params) {
                const self = this;
                let file = params.file;

                if(typeof(file.type) === 'undefined') {
                    return;
                }

                if(this.uploadType === 'image') {
                    const isJPG = file.type === 'image/jpeg' || 'image/jpg' || 'image/png';
                    const isLt2M = file.size / 1024 / 1024 < 2;
                    if (!isJPG) {
                        self.$message.error('上传头像图片只能是 JPG、JPEG和PHG 格式!');
                        return isJPG;
                    }
                    if (!isLt2M) {
                        self.$message.error('上传头像图片大小不能超过 2MB!');
                        return isLt2M;
                    }
                }else if(this.uploadType === 'video'){
                    const isVideo = file.type === 'video/mkv' || 'video/mp4' || 'video/rmvb';
                    const isLt10M = file.size / 1024 / 1024 < 10;
                    if (!isVideo) {
                        self.$message.error('上传视频只能是 MKV、MP4和RMVB 格式!');
                        return isVideo;
                    }
                    if (!isLt2M) {
                        self.$message.error('上传视频大小不能超过 10MB!');
                        return isLt10M;
                    }
                }

                const formData = new FormData();
                formData.append('file', file);

                self.axios({
                    url: 'http://vt.com/php/index.php/admin/users/uploadFile',
                    method: 'post',
                    data: formData,
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then(response => {
                    if (response.data.code === 0) {
                        self.$message.error(response.data['error'])
                    }else {
                        self.photoUrl = response.data['data'];
                        console.log(self.photoUrl);
                        self.editor.insertEmbed(self.editor.selection.savedRange.index, self.uploadType, self.photoUrl);  // 这个方法用来手动插入dom到编辑器里
                        self.$refs['upload'].clearFiles();    // 插入成功后清除input的内容
                    }
                })


            },
            // 点击图片ICON触发事件
            imgHandler(state) {
                this.addRange = this.$refs.myQuillEditor.quill.getSelection()
                if (state) {
                    let fileInput = document.getElementById('imgInput')
                    fileInput.click() // 加一个触发事件
                }
                this.uploadType = 'image'
            },
            // 点击视频ICON触发事件
            videoHandler(state) {
                this.addRange = this.$refs.myQuillEditor.quill.getSelection()
                if (state) {
                    let fileInput = document.getElementById('imgInput')
                    fileInput.click() // 加一个触发事件
                }
                this.uploadType = 'video'
            },

            // 提交用户注册数据到后端
            submitForm(formName) {
				// 区分 this
				const self = this;
                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        // 向后端传输数据（application/json）
                        let data = {
                                "username": self.form.username,
                                "part_id": self.form.value[1],
                                "title": self.form.title,
                                "content": self.content
                        };
                        self.axios.post('http://vt.com/php/index.php/admin/articles/articleSend',data).then(function (res) {
                            console.log(res)
                            if (res.status === 200 && res.data['error'] === '') {
                            	_g.toastMsg('success', '发送成功');
								setTimeout(() => {
									router.push({
                                        // 此处的 name 必须与 route.js 的相同
										name: 'Article',
										params: {
                                            // 文章id
											articleId:res.data['data'],
										}
                                    })
								}, 1500)
                            } else{
                            	// self.handleError(res.data);
                                _g.toastMsg('error', '发送失败');
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
                // self.$refs.upload.submit();
            },
            // 重置信息
            resetForm(formName) {
                this.$refs[formName].resetFields();
                // 清空正文
                var node = document.getElementsByClassName(("ql-editor"))[0];
                // console.log(node.innerHTML);
                // console.log(this.content);
                node.innerHTML = '';
            },
        },
        
        mounted() {
            this.editor = this.$refs.myQuillEditor.quill;

            // 为图片ICON绑定事件  getModule 为编辑器的内部属性
            this.$refs.myQuillEditor.quill.getModule('toolbar').addHandler('image', this.imgHandler)
            this.$refs.myQuillEditor.quill.getModule('toolbar').addHandler('video', this.videoHandler)  // 为视频ICON绑定事件
        },
        
    }
</script>

<style scoped>
    .el-breadcrumb{
        margin: 25px 0;
        letter-spacing: 0.1em;
        font-weight: 500;
    }
    .quill-editor {
        height: 500px;  
    }
    /* .ql-snow {
        border: 1px solid #00bfff63 !important;
    } */
    .ql-container.ql-snow{
        border: 1px solid #00bfff63;
    }
    .limit{
        width: 100px;
        float: right;
        position: relative;
        top: 30px;
        text-align: center;

    }
    .limit > span{
        color: #333;
    }
    /* .limit .now{
        color: #000;
    } */
    .limit .then{
        color: red;
    }
    .limit .max{
        color: red;
    }
    .btnG{
        margin-top: 100px;
    }
</style>