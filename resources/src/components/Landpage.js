import React, {Component} from 'react';
import { LinkContainer } from 'react-router-bootstrap';
import { translate } from 'react-i18next';
// 首頁

const Button = ReactBootstrap.Button,
      Grid = ReactBootstrap.Grid,
      Row = ReactBootstrap.Row,
      Col = ReactBootstrap.Col,
      Well = ReactBootstrap.Well;

class Landpage extends React.Component{
    constructor(props){
        super(props);
    }
    render(){
        const { t } = this.props;
        console.log(t);
        const branchData = [{
            name: "民生會館",
            time: "中午 PM 12:00 ~ 凌晨 AM 04:00 ",
            address: "台北市中山區民生東路 2 段 88 號",
            phone: "( 02 ) 2581- 3338"
        },{
            name: "光復會館",
            time: "上午 AM 11:00 ~ 凌晨 AM 03:00",
            address: "台北市松山區光復北路34 號",
            phone: "( 02 ) 2570- 9393"
        }],
        branches = branchData.map((branch, index)=>{
            return (
                
                <Well key={index}>
                <p>{branch.name}</p>
                <p>{"營業時間: "+branch.time}</p>
                <p>{"地址: "+branch.address}</p>
                <p>{"預約專線: "+branch.phone}</p>
                </Well>
                
            );
        });
        return(
              <Grid>
                <Row className="show-grid">
                    <div className="topContainer">
                    <h1>泰和殿</h1>
                    <h2>泰式養生會館</h2>
                        <div style={{width: "300px", margin: "0 auto", maxWidth: "90vw"}}>
                        <LinkContainer to="/reservation/0">
                            <Button bsStyle="primary" bsSize="large" block>
                                {t('book')}
                            </Button>
                        </LinkContainer>
                        </div>
                    </div>
                </Row>
                <Row className="show-grid">
                    <Col md={12}>
                        <h3>服務項目</h3>
                    </Col>
                    <Col md={5}>
                        <div className="img1"></div>
                    </Col>
                    <Col md={7}>

                        <Well>    
                            <p>泰式全身去角質+芳香精油SPA 經典價 2200 元
                            隔絕身心壓力及都市污染，泰和殿為您準備的洗塵儀式開始，您今天的 SPA 專屬時空已經替您預留。沐浴的開始即是SPA芳香舒壓的開始 ~~~ 
                            </p>
                        </Well>
                        <Well>
                            <p>泰式芳香精油SPA (2小時)
                            採用預先挑選的高級芳香精油，由專業的按摩師配合泰式舒壓使身體充分吸收美好的精油，並使全身徹底舒展放鬆
                            </p>
                        </Well>
                        <Well>
                            <p>泰式古法指壓 (2小時)
                            泰國古式按摩源於印度的瑜珈術泰式是在地板上或高床上進行，按摩師運用雙手、手指、手軸和膝蓋、臂膀和腳的力量，循著身體各部位施以按壓、揉捏、彎曲、扭轉等動作，使身體更加放鬆及達到舒壓的效果，並同時促進血液循環。
                            </p>
                        </Well>
                    </Col>
                </Row>
                <Row>
                <Col md={12}>
                    <h3>服務據點</h3>
                </Col>
                    <Col md={5}>
                        {branches}
                    </Col>
                    <Col md={7}>
                        <div className="img2"></div>
                    </Col>
                </Row>
                <Row className="show-grid">
                    <Col md={12}>
                        <h3>地圖</h3>
                    </Col>
                    <Col md={6}>
                        <Well>
                            地圖1
                        </Well>
                    </Col>
                    <Col md={6}>
                        <Well>    
                            地圖2
                        </Well>
                    </Col>
                </Row>
                <div className="topBg"></div>
              </Grid>
        );
    }
}
export default translate()(Landpage); 
