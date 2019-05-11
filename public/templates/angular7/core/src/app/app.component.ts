import {Component} from '@angular/core';
import axios from 'axios';
import {Environment} from './utils/environment';

export const httpClient = axios.create({
  baseURL: Environment.SERVER_URL,
  timeout: 30000,
});

export const httpAuthClient = axios.create({
  baseURL: Environment.SERVER_URL,
  timeout: 30000,
});

httpAuthClient.interceptors.request.use(function (request) {
  // Do something with request data
  const credentials = JSON.parse(localStorage.getItem('credentials'));
  request.headers.authorization = (typeof credentials !== 'undefined' && credentials) ? `Bearer ${credentials.access_token}` : '';
  return request;
}, function (error) {
  // Do something with request error
  return Promise.reject(error);
});

httpClient.interceptors.response.use(function (response) {
  // Do something with response data
  console.log('from_interceptor', response.data);
  return response;
}, function (error) {
  // Do something with response error
  console.log('error_from_interceptor', error.response.data);
  return Promise.reject(error);
});

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent {
  title = 'votever-webapp';
}
