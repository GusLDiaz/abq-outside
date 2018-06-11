import {Injectable} from "@angular/core";
import {HttpClient, HttpParams} from "@angular/common/http";
import {Observable} from "rxjs/Observable";
import {Trail} from "../classes/trail";
@Injectable()
export class TrailService {
	constructor(protected http: HttpClient) {
	}
	//define the API endpoint
	private trailUrl = "api/trail/";
	// call to the Trail API and get a Trail object by its id
	getTrailByTrailId(trailId : string): Observable<Trail> {
		return (this.http.get<Trail>(this.trailUrl + trailId));
	}
	// call to the Trail API and get a Trail object by its distance
	getTrailByDistance(distance: string): Observable<Trail[]> {
		return (this.http.get<Trail[]>(this.trailUrl, {params: new HttpParams().set("distance", distance)}));
	}
	// call to the Trail API and get all Trails
	getAllTrails(): Observable<Trail[]> {
		return (this.http.get<Trail[]>(this.trailUrl));
}
}