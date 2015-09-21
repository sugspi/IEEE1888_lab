/*
 * Copyright (c) 2013 Hideya Ochiai, the University of Tokyo,  All rights reserved.
 * 
 * Permission of redistribution and use in source and binary forms, 
 * with or without modification, are granted, free of charge, to any person 
 * obtaining the copy of this software under the following conditions:
 * 
 *  1. Any copies of this source code must include the above copyright notice,
 *  this permission notice and the following statement without modification 
 *  except possible additions of other copyright notices. 
 * 
 *  2. Redistributions of the binary code must involve the copy of the above 
 *  copyright notice, this permission notice and the following statement 
 *  in documents and/or materials provided with the distribution.  
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

/**
 * ieee1888_XMLgenerator.h
 * author: Hideya Ochiai
 * create: 2011-11-30
 * update: 2013-10-16
 */

#ifndef ___IEEE1888_XMLGENERATOR_H___20111130___
#define ___IEEE1888_XMLGENERATOR_H___20111130___

#define IEEE1888_QUERY_RQ 1
#define IEEE1888_QUERY_RS 2
#define IEEE1888_DATA_RQ  3
#define IEEE1888_DATA_RS  4

#define IEEE1888_SOAP_GEN_ERROR_UNKNOWN_MSG -1
#define IEEE1888_SOAP_GEN_ERROR_OVERFLOW    -2
int ieee1888_soap_gen(const ieee1888_transport* transport, int message, char* str_soap, int n);
int ieee1888_soap_error_gen(const char* error_msg,char* str_soap,int n);

#endif /* #ifndef ___IEEE1888_XMLGENERATOR_H___20111130___ */
