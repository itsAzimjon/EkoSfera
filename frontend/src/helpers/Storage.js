export const getItem=key=>{
try{
    return localStorage.getItem(key)
}
catch(error){
    console.log('error geting storage');
    return null
}
}
export const setItem=(key,date)=>{
    try {
        window.localStorage.setItem(key,date)
    } catch (error) {
        console.log("Error setting storage");
    }
    
}