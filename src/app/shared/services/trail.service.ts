import {Injectable} from "@angular/core";
import {HttpClient, HttpParams} from "@angular/common/http";
import {Observable} from "rxjs/Observable";
import {Trail} from "../classes/trail";
import {BehaviorSubject} from "rxjs/BehaviorSubject";
@Injectable()
export class TrailService {
	protected trailSubject : BehaviorSubject<Trail[]> = new BehaviorSubject<Trail[]>([]);
	public trailObserver : Observable<Trail[]> = this.trailSubject.asObservable();
	constructor(protected http: HttpClient) {
		this.getAllTrails().subscribe(trails => this.trailSubject.next(trails));
	}
	//define the API endpoint
	private trailUrl = "api/trail/";
	// call to the Trail API and get a Trail object by its id
	getTrailByTrailId(id : string): Observable<Trail> {
		return (this.http.get<Trail>(this.trailUrl + id));
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