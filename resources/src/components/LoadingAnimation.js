import React from "react";

const LoadingAnimation = (props) => {
    let dots = [];
    for (let i = 0; i < 10; i++) {
        dots.push(<div className="dot" key={i}></div>);
    }
    return (<div className="dotContainer">{dots}
        <div style={{
            lineHeight: '112px',
            borderRadius: ' 50%',
            width: '112px',
            height: '112px',
            backgroundColor: 'rgba(255, 255, 255, 0.3)',
            fontSize: '20px',
            textAlign: 'center'
        }}>{"系統判斷中"}</div>
    </div>);
}

module.exports = LoadingAnimation;