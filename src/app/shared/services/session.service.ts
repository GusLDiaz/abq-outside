import {Injectable} from "@angular/core";
import {HttpClient} from "@angular/common/http";
import {Status} from "../classes/status";
import {Observable} from "rxjs/Observable";

@Injectable()
export class SessionService {

	constructor(protected http:HttpClient) {}

	private sessionUrl = "api/temp-auth";

	setSession() : Observable<Response> {
		return (this.http.get(this.sessionUrl)
			.map((response : Response) => response));
	}
	// getSessionApiProfile() {
	// 	return (this.http.get<>(this.sessionUrl));
	// }
}