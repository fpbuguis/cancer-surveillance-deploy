import{d as n,y as O,o as q,e as w,b as S,w as A,F as M,a as l,q as a,B as u,v as P,g as N,k as z}from"./app-42536e63.js";import I from"./Layout-1c104a2a.js";import{E as H}from"./jspdf.es.min-40bb167d.js";import{_ as W}from"./_plugin-vue_export-helper-c27b6911.js";import"./DropdownLink-ed97a0d6.js";import"./index-189aeacd.js";/* empty css                                                               */const k={__name:"LabRequestPage",setup($){const i=n(""),s=n(""),v=n(""),d=n(""),m=n(""),p=n(""),r=n(""),x=n(""),b=n(""),F=n("");n("");const y=n(""),V=n(""),f=n(""),g=n(""),C=n(""),R=n(""),U=n(""),L=()=>{const t=new H,e=D=>D?String(D):"N/A",B=16;t.setFontSize(B),t.setFont("helvetica","bold");const o="LABORATORY REQUEST",T=t.getTextWidth(o),E=(t.internal.pageSize.width-T)/2;t.text(o,E,20),t.setFontSize(12),t.setFont("helvetica","bold"),t.text("Ward/Room/Bed No/OPD Clinic:",20,40),t.setFont("helvetica","normal"),t.text(e(i.value),100,40),t.setFont("helvetica","bold"),t.text("Name (Last, First, MI):",20,50),t.setFont("helvetica","normal"),t.text(`${e(s.value)}, ${e(v.value)} ${e(d.value)}`,100,50),t.setFont("helvetica","bold"),t.text("Contact Number:",20,60),t.setFont("helvetica","normal"),t.text(e(U.value),100,60),t.setFont("helvetica","bold"),t.text("Age:",20,70),t.setFont("helvetica","normal"),t.text(e(m.value),100,70),t.setFont("helvetica","bold"),t.text("Sex:",20,80),t.setFont("helvetica","normal"),t.text(e(p.value),100,80),t.setFont("helvetica","bold"),t.text("Hospital Case No:",20,90),t.setFont("helvetica","normal"),t.text(e(r.value),100,90),t.setFont("helvetica","bold"),t.text("Birth Date:",20,100),t.setFont("helvetica","normal"),t.text(e(x.value),100,100),t.setFont("helvetica","bold"),t.text("Diagnosis:",20,110),t.setFont("helvetica","normal"),t.text(e(b.value),100,110),t.setFont("helvetica","bold"),t.text("Requested By:",20,120),t.setFont("helvetica","normal"),t.text(e(F.value),100,120),t.setFont("helvetica","bold"),t.text("Laboratory Examination Desired:",20,130),t.setFont("helvetica","normal"),t.text(e(y.value),100,130),t.setFont("helvetica","bold"),t.text("Specimen:",20,140),t.setFont("helvetica","normal"),t.text(e(V.value),100,140),t.setFont("helvetica","bold"),t.text("Site of Collection:",20,150),t.setFont("helvetica","normal"),t.text(e(f.value),100,150),t.setFont("helvetica","bold"),t.text("Collected By:",20,160),t.setFont("helvetica","normal"),t.text(e(g.value),100,160),t.setFont("helvetica","bold"),t.text("Time Collected:",20,170),t.setFont("helvetica","normal"),t.text(e(C.value),100,170),t.setFont("helvetica","bold"),t.text("Date Collected:",20,180),t.setFont("helvetica","normal"),t.text(e(R.value),100,180),t.save("Laboratory_Request_Form.pdf"),i.value="",s.value="",v.value="",d.value="",m.value="",p.value="",r.value="",x.value="",b.value="",F.value="",y.value="",V.value="",f.value="",g.value="",C.value="",R.value="",U.value=""};return(t,e)=>{const B=O("Head");return q(),w(M,null,[S(B,{title:"Laboratory Request Form"}),S(I,null,{default:A(()=>[e[34]||(e[34]=l("div",{class:"page-heading"},[l("h1",{class:"main-heading"},"LABORATORY REQUEST")],-1)),l("form",{onSubmit:z(L,["prevent"]),class:"form-container"},[l("div",null,[e[17]||(e[17]=l("label",null,"Ward/Room/Bed No/OPD Clinic",-1)),a(l("input",{"onUpdate:modelValue":e[0]||(e[0]=o=>i.value=o),type:"text"},null,512),[[u,i.value]])]),l("div",null,[e[18]||(e[18]=l("label",null,"Name (Last, First, MI)",-1)),a(l("input",{"onUpdate:modelValue":e[1]||(e[1]=o=>s.value=o),type:"text"},null,512),[[u,s.value]]),a(l("input",{"onUpdate:modelValue":e[2]||(e[2]=o=>v.value=o),type:"text"},null,512),[[u,v.value]]),a(l("input",{"onUpdate:modelValue":e[3]||(e[3]=o=>d.value=o),type:"text"},null,512),[[u,d.value]])]),l("div",null,[e[19]||(e[19]=l("label",null,"Contact Number",-1)),a(l("input",{"onUpdate:modelValue":e[4]||(e[4]=o=>U.value=o),type:"text"},null,512),[[u,U.value]])]),l("div",null,[e[20]||(e[20]=l("label",null,"Age:",-1)),a(l("input",{"onUpdate:modelValue":e[5]||(e[5]=o=>m.value=o),type:"number"},null,512),[[u,m.value]])]),l("div",null,[e[22]||(e[22]=l("label",null,"Sex:",-1)),a(l("select",{"onUpdate:modelValue":e[6]||(e[6]=o=>p.value=o)},e[21]||(e[21]=[l("option",{value:"Male"},"Male",-1),l("option",{value:"Female"},"Female",-1)]),512),[[P,p.value]])]),l("div",null,[e[23]||(e[23]=l("label",null,"Hospital Case No.",-1)),a(l("input",{"onUpdate:modelValue":e[7]||(e[7]=o=>r.value=o),type:"text"},null,512),[[u,r.value]])]),l("div",null,[e[24]||(e[24]=l("label",null,"Birth Date",-1)),a(l("input",{"onUpdate:modelValue":e[8]||(e[8]=o=>x.value=o),type:"date"},null,512),[[u,x.value]])]),l("div",null,[e[25]||(e[25]=l("label",null,"Diagnosis:",-1)),a(l("input",{"onUpdate:modelValue":e[9]||(e[9]=o=>b.value=o),type:"text"},null,512),[[u,b.value]])]),l("div",null,[e[26]||(e[26]=l("label",null,[N(" Requested By: "),l("br"),l("span",{style:{"font-size":"small","font-weight":"normal"}},"(DOCTOR'S SIGNATURE OVER TRODAT)")],-1)),a(l("input",{"onUpdate:modelValue":e[10]||(e[10]=o=>F.value=o),type:"text"},null,512),[[u,F.value]])]),l("div",null,[e[27]||(e[27]=l("label",null,[N(" Laboratory Examination Desired "),l("br"),l("span",{style:{"font-size":"small","font-weight":"normal"}},"(Use one Request Form per specimen)")],-1)),a(l("input",{"onUpdate:modelValue":e[11]||(e[11]=o=>y.value=o),type:"text"},null,512),[[u,y.value]])]),l("div",null,[e[28]||(e[28]=l("label",null,"Specimen",-1)),a(l("input",{"onUpdate:modelValue":e[12]||(e[12]=o=>V.value=o),type:"text"},null,512),[[u,V.value]])]),l("div",null,[e[29]||(e[29]=l("label",null,"Site of Collection",-1)),a(l("input",{"onUpdate:modelValue":e[13]||(e[13]=o=>f.value=o),type:"text"},null,512),[[u,f.value]])]),l("div",null,[e[30]||(e[30]=l("label",null,"Collected By",-1)),a(l("input",{"onUpdate:modelValue":e[14]||(e[14]=o=>g.value=o),type:"text"},null,512),[[u,g.value]])]),l("div",null,[e[31]||(e[31]=l("label",null,"Time Collected",-1)),a(l("input",{"onUpdate:modelValue":e[15]||(e[15]=o=>C.value=o),type:"time"},null,512),[[u,C.value]])]),l("div",null,[e[32]||(e[32]=l("label",null,"Date Collected",-1)),a(l("input",{"onUpdate:modelValue":e[16]||(e[16]=o=>R.value=o),type:"date"},null,512),[[u,R.value]])]),e[33]||(e[33]=l("button",{type:"submit",class:"submit-btn"},"Generate PDF",-1))],32)]),_:1})],64)}}},Z=W(k,[["__scopeId","data-v-6a60d067"]]);export{Z as default};