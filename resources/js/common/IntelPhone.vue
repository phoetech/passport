<template>
  <div
    :class="cClass"
    class="intl-phone-input allow-dropdown"
    v-on:mouseleave="hideList"
  >
    <div
      class="flag-container"
      v-on:click.prevent="toggeList"
    >
      <div class="selected-flag">
        <div v-bind:class="`iti-flag ${countryCode}`"></div>
        <!-- <div class="iti-dial-code">+{{dialCode}}</div> -->
        <div v-bind:class="`iti-arrow${isVisiblePanel ? ' up' : ''}`" v-if="!disabled"></div>
      </div>
      <ul class="country-list" v-if="isVisiblePanel">
        <li
          v-for="item in itemsData.frontItems"
          v-bind:class="`country${item.code === countryCode ? ' highlight' : ''}`"
          v-on:click.stop="setCode(item)"
        >
          <div class="flag-box">
            <div v-bind:class="`iti-flag ${item.code}`"></div>
          </div>
          <span class="country-name">{{ item.name }}</span>
          <span class="dial-code">+{{ item.dialCode }}</span>
        </li>
        <li class="divider" v-if="itemsData.needSeparator"></li>
        <li
          v-for="item in itemsData.otherItems"
          v-bind:class="`country${item.code === countryCode ? ' highlight' : ''}`"
          v-on:click.stop="setCode(item)"
        >
          <div class="flag-box">
            <div v-bind:class="`iti-flag ${item.code}`"></div>
          </div>
          <span class="country-name">{{ item.name }}</span>
          <span class="dial-code">+{{ item.dialCode }}</span>
        </li>
      </ul>
    </div>
    <input type="text"
      autocomplete="off"
      v-bind:name="name"
      v-model="phoneNumber"
      v-on:input="$emit('input', $event.target.value);handleChangePhoneNumber($event)"
      v-bind:placeholder="placeholder"
      :maxlength="phoneLength"
    />
  </div>
</template>

<script>
/* eslint-disable no-unused-vars */
const has = Object.hasOwnProperty;

/* eslint-disable object-curly-newline */
import { phonesData } from "../data/phonesData.js";
/* eslint-enable object-curly-newline, no-unused-vars */
let counter = 0;
export default {
    props: {
        name: {
            type: String,
            default() {
                counter += 1;
                return `tel-input-${counter}`;
            }
        },
        value: {
            type: [Number, String],
            default: ""
        },
        availbleOnly: {
            type: Array,
            default: null
        },
        toFront: {
            type: Array,
            default() {
                return [];
            }
        },
        code: {
            type: String,
            default: null
        },
        onChange: {
            type: Function,
            default: null
        },
        onInit: {
            type: Function,
            default: null
        },
        cClass: {
            type: String,
            default: ""
        },
        placeholder: {
            type: String,
            default: ""
        },
        disabled: {
            type: Boolean,
            default: false
        },
        phoneLength: {
            type: Number,
            default: 12
        }
    },
    watch: {
        value(val, old) {
            if (val != old) {
                this.phoneNumber = val;
            }
        }
    },

    data() {
        const { code, availableOnly, value } = this;

        let tmpPhonesData = _.assign({}, phonesData);

        if (_.isArray(availableOnly)) {
            tmpPhonesData = {};
            availableOnly.forEach(item => {
                if (has.call(phonesData, item)) {
                    tmpPhonesData[item] = phonesData[item];
                }
            });
        }
        this.availableData = tmpPhonesData;

        const needCode = has.call(this.availableData, code)
            ? code
            : _.keys(this.availableData)[0];

        if (!needCode) {
            throw new Error(
                'Available data is empty.Please set correct "availableOnly" attribute [vue-phone-input]'
            );
        }
        return {
            phoneNumber: value,
            countryCode: needCode,
            isVisiblePanel: false,
            dialCode: this.availableData[needCode].dialCode
        };
    },
    computed: {
        intlData() {
            this.phoneNumber = this.value;
            return this.availableData[this.countryCode];
        },
        itemsData() {
            const frontItems = [];
            const otherItems = [];
            const phonesDataCopy = _.assign({}, this.availableData);
            this.toFront.forEach(code => {
                const item = phonesDataCopy[code];
                if (_.isObject(item)) {
                    frontItems.push(item);
                    delete phonesDataCopy[code];
                }
            });
            Object.values(phonesDataCopy).forEach(item =>
                otherItems.push(item)
            );
            const needSeparator =
                frontItems.length > 0 && otherItems.length > 0;
            return {
                frontItems,
                otherItems,
                needSeparator
            };
        }
    },
    methods: {
        handleChangePhoneNumber(event) {
            if (_.isFunction(this.onChange)) {
                this.onChange(this);
            }
        },
        setCode(item) {
            this.countryCode = item.code;
            this.dialCode = item.dialCode;
            if (_.isFunction(this.onChange)) {
                this.onChange(this);
            }
            this.hideList();
        },
        showList() {
            this.isVisiblePanel = true;
        },
        hideList() {
            this.isVisiblePanel = false;
        },
        toggeList() {
            if (this.disabled) {
                return false;
            }
            let a = !this.isVisiblePanel;
            (this.isVisiblePanel ? this.hideList : this.showList)();
        },
        changeCode(code) {
            this.countryCode = code;
            this.dialCode = this.availableData[code].dialCode;
        }
    },
    mounted() {
        if (_.isFunction(this.onInit)) {
            this.onInit(this);
        }
    }
};
</script>
