import{s as u,q as n,x as d,o as p,e as i}from"./app-42536e63.js";const m=["value"],k={__name:"Checkbox",props:{checked:{type:[Array,Boolean],default:!1},value:{type:String,default:null}},emits:["update:checked"],setup(e,{emit:c}){const s=c,l=e,t=u({get(){return l.checked},set(o){s("update:checked",o)}});return(o,a)=>n((p(),i("input",{"onUpdate:modelValue":a[0]||(a[0]=r=>t.value=r),type:"checkbox",value:e.value,class:"rounded border-gray-300 text-[#b04748] shadow-sm focus:ring-[#9f2123]"},null,8,m)),[[d,t.value]])}};export{k as _};
