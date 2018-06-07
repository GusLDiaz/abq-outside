import {NgModule} from "@angular/core";
import {BrowserModule} from "@angular/platform-browser";
import {FormsModule, ReactiveFormsModule} from "@angular/forms";
import {HttpClientModule} from "@angular/common/http";
import {AppComponent} from "./app.component";
//allApp components- Routes
import {allAppComponents, appRoutingProviders, routing} from "./app.routes";



const moduleDeclarations = [AppComponent];


@NgModule({
	imports:      [BrowserModule, HttpClientModule,ReactiveFormsModule, FormsModule, routing],
	declarations: [...moduleDeclarations, ...allAppComponents],
	bootstrap:    [AppComponent],
	providers:    [appRoutingProviders]
})
export class AppModule {}