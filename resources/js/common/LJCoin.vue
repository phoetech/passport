<template>
    <span class="coinShow"><label class="zoomIn animated" v-if="currentCoin">+{{currentCoin[3]|currency}}</label></span>
</template>

<script>
import { EventBus } from "../common/EventBus.js";
export default {
  mounted() {
    this.showCoin();
    let soundCookie = this.$cookie.get("sound");
    this.soundStatus = soundCookie ? soundCookie : "on";
    EventBus.$on("sound-switch", status => {
      this.soundStatus = status;
      this.$cookie.set("sound", status, 365);
    });
  },
  data() {
    return {
      coinList: [],
      currentCoin: null,
      isDoing: false,
      user: window.user,
      soundStatus: "on",
      sound: new Audio("/img/keys.mp3")
    };
  },
  methods: {
    showCoin() {
      if (this.isDoing) {
        return;
      }
      if (this.coinList.length > 0) {
        this.isDoing = true;
        var c = this.coinList.pop();
        if (this.soundStatus == "on") {
          this.sound.play();
        }
        this.currentCoin = c;
        setTimeout(this.hideCoin, 1500);
      }
    },
    hideCoin() {
      EventBus.$emit("update-coin", this.currentCoin);
      this.currentCoin = null;
      this.isDoing = false;
      setTimeout(this.showCoin, 1000);
    },
    addCoin(data) {
      // let c = this.getRandomInt();
      // data = [10000001, this.user.total_reward + c, this.user.balance + c, c];
      this.coinList.unshift(data);
      this.showCoin();
    },
    getRandomInt() {
      return Math.floor(Math.random() * (500 - 5 + 1)) + 5;
    }
  }
};
</script>
<style type="text/css">
.coinShow {
  /*position: absolute;*/
  text-align: center;
  width: 75px;
  margin: auto;
  z-index: 1000;
}
.coinShow label {
  position: absolute;
  color: #3bc9d6;
  /*-webkit-text-stroke: 1px #333;*/
  /*text-stroke: 1px #333;*/
  font-weight: bold;
}

@keyframes zoomIn {
  from {
    opacity: 0;
    transform: scale3d(0.7, 0.7, 0.7);
    transform: translate(0px, -25px);
  }

  30% {
    opacity: 1;
  }
  70% {
    opacity: 1;
    transform: translate(0px, -93px);
  }
  to {
    opacity: 0;
    transform: translate(0px, -93px);
  }
}

.animated {
  -webkit-animation-duration: 2s;
  animation-duration: 2s;
  -webkit-animation-fill-mode: both;
  animation-fill-mode: both;
}

.animated.infinite {
  -webkit-animation-iteration-count: infinite;
  animation-iteration-count: infinite;
}

.zoomIn {
  animation-name: zoomIn;
  font-size: 20px;
}
.addCoin {
  /*border: 1px solid #000;*/
  /*display: none;*/
}
</style>
