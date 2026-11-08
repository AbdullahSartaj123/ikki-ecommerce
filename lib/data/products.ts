export type Product={slug:string;name:string;notes:string;description:string;price:number;size:string;tone:string;category:string};
export const products:Product[]=[
{slug:"legendary-profile",name:"Legendary Profile",notes:"Bold · Magnetic · Timeless",description:"A confident signature with depth, warmth and an unmistakable presence.",price:4990,size:"100 ml",tone:"bg-[#e8e0d2]",category:"Woody"},
{slug:"informal",name:"Informal",notes:"Effortless · Fresh · Modern",description:"An easy confidence, composed for the everyday and remembered long after.",price:4490,size:"100 ml",tone:"bg-[#dfe3dc]",category:"Fresh"},
{slug:"amane",name:"Amane",notes:"Soft · Refined · Enveloping",description:"A softer expression of character, intimate and quietly unforgettable.",price:4790,size:"100 ml",tone:"bg-[#e9dfdf]",category:"Floral"},
{slug:"sasha",name:"Sasha",notes:"Radiant · Expressive · Distinctive",description:"Bright energy meets a lasting trail in a fragrance made to stand apart.",price:4590,size:"100 ml",tone:"bg-[#e7e1d2]",category:"Amber"}];
export function getProduct(slug:string){return products.find(product=>product.slug===slug);}