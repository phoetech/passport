<template>
    <div :class="custom">
        <span class="circle" v-for="s in steps" :class="{'c-hand': change, 'ys-step-sel': currentStep == s, 'ys-step-unsel': currentStep != s}" @click="changeTo(s)"></span>
    </div>
</template>

<script>
const isFunction = maybeFunc =>
    Object.prototype.toString.call(maybeFunc) === "[object Function]";
export default {
    props: {
        steps: {
            type: Array,
            default: []
        },
        custom: {
            type: String,
            default: "ys-step"
        },
        size: {
            type: Number,
            default: 15
        },
        current: {
            type: String,
            default: null
        },
        change: {
            type: Boolean,
            default: false
        }
    },
    mounted() {
        this.currentStep = this.current;
    },
    watch: {
        current(val, old) {
            if (val != old) {
                this.currentStep = this.current;
            }
        }
    },
    data() {
        return {
            currentStep: null
        };
    },
    methods: {
        changeTo(step) {
            if (this.change) {
                this.$emit("change", step);
            }
        }
    }
};
</script>
<style type="text/css">
.ys-step {
}
.ys-step .c-hand {
    cursor: pointer;
}
.ys-step .circle {
    background-color: #ececec;
    padding: 0px;
    margin: 3px;
    width: 15px;
    height: 15px;
    border-radius: 7px;
    display: block;
    float: left;
}
.ys-step .current {
    background-color: #3bc9d6 !important;
    width: 30px !important;
}
/* Safari 4.0 - 8.0 */
@-webkit-keyframes sel {
    from {
        width: 15px;
        background-color: #ececec;
    }
    to {
        width: 30px;
        background-color: #3bc9d6;
    }
}
/* Standard syntax */
@keyframes sel {
    from {
        width: 15px;
        background-color: #ececec;
    }
    to {
        width: 30px;
        background-color: #3bc9d6;
    }
}

/* Safari 4.0 - 8.0 */
@-webkit-keyframes unsel {
    from {
        width: 30px;
        background-color: #3bc9d6;
    }
    to {
        width: 15px;
        background-color: #ececec;
    }
}
/* Standard syntax */
@keyframes unsel {
    from {
        width: 30px;
        background-color: #3bc9d6;
    }
    to {
        width: 15px;
        background-color: #ececec;
    }
}
.ys-step-sel {
    animation-name: sel;
    animation-duration: 0.5s;
    animation-fill-mode: forwards;
}
.ys-step-unsel {
    animation-name: unsel;
    animation-duration: 0.5s;
    animation-fill-mode: forwards;
}
</style>
