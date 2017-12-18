import { LinkContainer } from 'react-router-bootstrap';
import { translate } from 'react-i18next';
import i18n from '../i18n';
import {connect} from "react-redux";

// 首頁

const Button = ReactBootstrap.Button,
      Grid = ReactBootstrap.Grid,
      Row = ReactBootstrap.Row,
      Col = ReactBootstrap.Col;

class Landpage extends React.Component{
    constructor(props){
        super(props);
    }
    render(){
        const { t } = this.props;

        const branchData = [{
            name: t("location1"),
            time: "12:00 p.m. ~ 04:00 a.m.",
            address: t('watpoAddr1'),
            phone: "( 02 ) 2581- 3338"
        },{
            name: t("location2"),
            time: "11:00 a.m. ~ 03:00 a.m.",
            address: t('watpoAddr2'),
            phone: "( 02 ) 2570- 9393"
        }],
        branches = branchData.map((branch, index)=>{
            return (
                <div>
                <h4><i className="fa fa-caret-right" aria-hidden="true"></i>{" "+branch.name}</h4>
                <div className="contentBlock" key={index}>
                <p>
                {t('bussinessHours') + " : " +branch.time}<br/>
                {t('address') + " : " +branch.address}<br/>
                {t('registrationNumber') + " : " +branch.phone}</p>
                </div>
                </div>
            );
        });
        return(
              <Grid>
                <Row className="landpage_top">
                    <div className="topContainer">
                    <h1 className="title">{t("Watpo")}</h1>
                    <h2 className="sub">{t("ThaiTraditionalMedicalMassage")}</h2>
                        <div className="landpageBtnContainer">
                        <LinkContainer to="/reservation/0">
                            <Button bsStyle="primary" bsSize="large" className="btn mainBtn" block>
                                <i className="fa fa-pencil" aria-hidden="true"></i>
                                {"  " + t('book')}
                            </Button>
                        </LinkContainer>
                        </div>
                    </div>
                </Row>
                <Row className="landpage_section">
                    <Col md={12}>
                        <h3 className="sectionTitle"><i className="fa fa-list-alt" aria-hidden="true"></i>{" " + t("services")}</h3>
                    </Col>
                    <Col md={5}>
                        <div className="img"></div>
                    </Col>
                    <Col md={7}>
                        <div className="sectionItem">
                            <h4><i className="fa fa-caret-right" aria-hidden="true"></i> {t("massageAndSpa")+" (2" + t("hours") +")"}</h4>
                            <h5>{t("price1")+" "+t("servicePrice1")}</h5>
                            <div className="contentBlock">    
                                <p>
                                {t("massageAndSpaDes")}
                                </p>
                            </div>
                        </div>
                        <div className="sectionItem">
                            <h4><i className="fa fa-caret-right" aria-hidden="true"></i> {t("ThaiOilMassage")+" (2" + t("hours") +")"}</h4>
                            <h5>{t("price2") +" "+t("servicePrice2")}</h5>
                            <div className="contentBlock">
                                <p>
                                    {t("ThaiOilMassageDes")}
                                </p>
                            </div>
                        </div>
                        <div className="sectionItem">
                            <h4><i className="fa fa-caret-right" aria-hidden="true"></i>{t("ThaiTraditionalMassage")+" (2" + t("hours") +")"}</h4>
                            <h5>{t("price3") + " " + t("servicePrice3")}</h5>
                            <div className="contentBlock">
                                <p>
                                {t("ThaiTraditionalMassageDes")}
                                </p>
                            </div>
                        </div>
                    </Col>
                </Row>
                <Row className="landpage_section">
                <Col md={12}>
                    <h3 className="sectionTitle"><i className="fa fa-building-o" aria-hidden="true"></i>{" "+t("locations")}</h3>
                </Col>
                    <Col md={5}>
                        {branches}
                    </Col>
                    <Col md={7}>
                        <div className="contentBlock sectionItem map">(map)</div>
                    </Col>
                </Row>
                <div className="topBg" data-arrow="&#xf078;"></div>
                <Row className="footerContainer">
                <Col md={12}>
                <footer>
                    <a href="https://www.facebook.com/watpomassages" target="_blank"><i className="fa fa-facebook-square" aria-hidden="true"></i></a><br/>
                    {t("WatpoThaiTraditionalMedicalMassage")}<br/>
                    {t("registrationNumber")}: ( 02 ) 2581- 3338<br/>
                    {t("bussinessHours")}: 12:00 p.m. ~ 04:00 a.m.<br/>
                    {t("watpoAddr1")}
                </footer>
                </Col>
                </Row>
              </Grid>
        );
    }
}


export default translate()(Landpage); 
