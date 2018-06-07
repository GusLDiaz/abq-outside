import {NgModule} from "@angular/core";
import {BrowserModule} from "@angular/platform-browser";
import {FormsModule, ReactiveFormsModule} from "@angular/forms";
import {HttpClientModule} from "@angular/common/http";
import {AppComponent} from "./app.component";
//allApp components- Routes
import {allAppComponents, appRoutingProviders, routing} from "./app.routes";
import {NguiMapModule} from "@ngui/map";



const moduleDeclarations = [AppComponent];


@NgModule({
	imports:      [BrowserModule, HttpClientModule,ReactiveFormsModule, FormsModule, routing,NguiMapModule.forRoot({apiUrl: 'https://maps.google.com/maps/api/js?key=AIzaSyCZrOzc0T3D0D75_0BhmWijZXCqHK3DC08'})],
	declarations: [...moduleDeclarations, ...allAppComponents],
	bootstrap:    [AppComponent],
	providers:    [appRoutingProviders]
})
export class AppModule {}