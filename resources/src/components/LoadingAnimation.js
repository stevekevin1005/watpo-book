import React from "react";

const LoadingAnimation = (props)=>{
    let dots = [];
    for (let i = 0; i < 10 ; i++) {
        dots.push(<div className="dot" key={i}></div>);
    }
    return (<div className="dotContainer">{dots}</div>);
}

module.exports = LoadingAnimation;