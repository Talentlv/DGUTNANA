<template>
	<div id="app">
		<!-- 可以不用 bounce -->
		<router-view></router-view>
		<!--<transition name="bounce">-->
		<!--<router-view></router-view>-->
		<!--</transition>-->
	</div>
</template>

<script>
	export default {
		name: 'app',
		components: {
		}
	}
</script>

<style>
	@import "assets/css/font-awesome.min.css";
	@import "assets/css/top-head.css";
	.bounce-enter-active {
		animation: bounce-in .5s;
	}

	.bounce-leave-active {
		animation: bounce-out .5s;
	}

	@keyframes bounce-in {
		0% {
			transform: scale(0);
		}
		50% {
			transform: scale(0.5);
		}
		100% {
			transform: scale(1);
		}
	}

	@keyframes bounce-out {
		0% {
			transform: scale(1);
		}
		50% {
			transform: scale(0.5);
		}
		100% {
			transform: scale(0);
		}
	}

	body {
		margin: 0;
		padding: 0;
		/*background: #f7f9fb;*/
		font-family: Helvetica Neue, Helvetica, PingFang SC, Hiragino Sans GB, Microsoft YaHei, SimSun, sans-serif;
		font-weight: 400;
		-webkit-font-smoothing: antialiased;
	}

	#app {
		width: 100%;
		position: absolute;
		top: 0;
		bottom: 0;

	}

	.el-submenu [class^=fa] {
		vertical-align: baseline;
		margin-right: 10px;
	}

	.el-menu-item [class^=fa] {
		vertical-align: baseline;
		margin-right: 10px;
	}
</style>