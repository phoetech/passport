<template>
    <div class="col-12 col-md-8 all-coin mt-sm-0 mt-md-3" >
      <ul class="d-md-block d-none mr-3 float-right">
        <li class="position-relative">
          <label class="value ">{{ user.total_reward | currency(false)}}</label>
          <label class="title">{{$t('title.totalRewards')}}</label>
          <YSCoin ref="showCoin"></YSCoin>
        </li>
        <li>
          <label class="value">{{ user.members | numberFormat}}</label>
          <label class="title">{{$t('title.totalFriends')}}</label>
        </li>
        <li>
          <label class="value">{{ user.balance | currency(false)}}</label>
          <label class="title">{{$t('title.totalBalance')}}</label>
        </li>
      </ul>
      
      <div class="row no-gutters d-md-none text-center m-header">
        <div class="col-4">
          <label class="value text-white">{{ user.total_reward | currency(false)}}</label>
          <label class="title text-white">{{$t('title.totalRewards')}}</label>
          <YSCoin ref="showCoin2"></YSCoin>
        </div>
        <div class="col-4">
          <label class="value text-white">{{ user.members | numberFormat}}</label>
          <label class="title text-white">{{$t('title.totalFriends')}}</label>
        </div>
        <div class="col-4">
          <label class="value text-white">{{ user.balance | currency(false)}}</label>
          <label class="title text-white">{{$t('title.totalBalance')}}</label>
        </div>
      </div>
      
    </div>
</template>

<script>
import { EventBus } from "../common/EventBus.js";
export default {
  mounted() {
    this.$nextTick(function() {
      EventBus.$emit("register-showCoin", this.$refs.showCoin);
      EventBus.$emit("register-showCoin", this.$refs.showCoin2);
    });
    EventBus.$on("update-coin", data => {
      this.user.balance = data[2];
      this.user.balance_frozen += data[3];
      this.user.total_reward = data[1];
    });
    // EventBus.$on("add-member", amount => {
    // this.user.members++;
    // });
  },
  data() {
    return {
      user: window.user
    };
  },
  methods: {}
};
</script>
<style type="text/css">
</style>
