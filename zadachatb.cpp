/* Includes Standard C++  */

#include <stdlib.h>
#include <iostream>

/*
  Include directly the different
  headers from cppconn/ and mysql_driver.h + mysql_util.h
  (and mysql_connection.h). This will reduce your build time!
*/

#include "mysql_connection.h" 
#include <cppconn/driver.h>
#include <cppconn/exception.h>
#include <cppconn/resultset.h>
#include <cppconn/statement.h>
#include <cppconn/prepared_statement.h>

using namespace std;

bool checkCaptcha(string& captcha, string& user_captcha)
{
    return captcha.compare(user_captcha) == 0;
}


string generateCaptcha(int n)
{
    time_t t;
    srand((unsigned)time(&t));

    const char* chrs = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

    string captcha = "";
    while (n--)
        captcha.push_back(chrs[rand() % 62]);

    return captcha;
}

int login() {
    string email, passw, id="-1", cap, cap_u;
    bool val = 1;
    cap = generateCaptcha(4);
    std::cout << "Enter captcha: " + cap << endl;
    cin >> cap_u;
    if (cap_u != cap) {

        cout << "Wrong captcha!" << endl;
        return -1;
    }
    else {

        std::cout << "Enter an email:" << endl;
        cin >> email;
        std::cout << "Enter password:" << endl;
        cin >> passw;
        try {
            sql::Driver* driver;
            sql::Connection* con;
            sql::Statement* stmt;
            sql::ResultSet* res;

            
            driver = get_driver_instance();
            con = driver->connect("tcp://127.0.0.1:3306", "root", "");
            
            con->setSchema("test");

            stmt = con->createStatement();
            //////////////////////////////////////////////////////////////////////////////////////////////
            res = stmt->executeQuery("SELECT * FROM users WHERE email ='" + email + "';"); 
            if (passw == res->getString("password")) {
                id = res->getString("id");

                return stoi(id);
            }
            else {
                return -1;
            }

            delete res;
            delete stmt;
            delete con;

        }
        catch (sql::SQLException& e) {
            std::cout << "# ERR: SQLException in " << __FILE__;
            std::cout << "(" << __FUNCTION__ << ") on line " << __LINE__ << endl;
            std::cout << "# ERR: " << e.what();
            std::cout << " (MySQL error code: " << e.getErrorCode();
            std::cout << ", SQLState: " << e.getSQLState() << " )" << endl;
        }
    }
}

void reg(string name, string pass, string email) {
        try {
            sql::Driver* driver;
            sql::Connection* con;
            sql::ResultSet* res;
            sql::PreparedStatement* prep_stmt;
            
            driver = get_driver_instance();
            con = driver->connect("tcp://127.0.0.1:3306", "root", "");
            
            con->setSchema("test");
            prep_stmt = con->prepareStatement("INSERT INTO users(email, name, password) VALUES (?, ?, ?)");
            prep_stmt->setString(1, email);
            prep_stmt->setString(2, name);
            prep_stmt->setString(3, pass);
            prep_stmt->executeQuery();
            delete con;
            delete prep_stmt;

        }
        catch (sql::SQLException& e) {
            std::cout << "# ERR: SQLException in " << __FILE__;
            std::cout << "(" << __FUNCTION__ << ") on line " << __LINE__ << endl;
            std::cout << "# ERR: " << e.what();
            std::cout << " (MySQL error code: " << e.getErrorCode();
            std::cout << ", SQLState: " << e.getSQLState() << " )" << endl;
        }
}

int pass() {
    string email, passw, id = "-1", cap, cap_u;
    bool val = 1;
    cap = generateCaptcha(4);
    std::cout << "Enter captcha: " + cap << endl;
    cin >> cap_u;
    if (cap_u != cap) {

        cout << "Wrong captcha!" << endl;
        return -1;
    }
    else {

        std::cout << "Enter an email:" << endl;
        cin >> email;

        try {
            sql::Driver* driver;
            sql::Connection* con;
            sql::Statement* stmt;
            sql::ResultSet* res;

            
            driver = get_driver_instance();
            con = driver->connect("tcp://127.0.0.1:3306", "root", "");
           
            con->setSchema("test");

            stmt = con->createStatement();
            //////////////////////////////////////////////////////////////////////////////////////////////
            res = stmt->executeQuery("SELECT * FROM users WHERE email ='" + email + "';");
            if (res!=0) {
                passw = generateCaptcha(8);
                stmt->executeQuery("REPLACE INTO users (id, password) VALUES (" + res->getString("id") + "," + passw + ");");

                
            }
            else {
                return 0;
            }

            delete res;
            delete stmt;
            delete con;

        }
        catch (sql::SQLException& e) {
            std::cout << "# ERR: SQLException in " << __FILE__;
            std::cout << "(" << __FUNCTION__ << ") on line " << __LINE__ << endl;
            std::cout << "# ERR: " << e.what();
            std::cout << " (MySQL error code: " << e.getErrorCode();
            std::cout << ", SQLState: " << e.getSQLState() << " )" << endl;
        }
    }
}

bool checkvalidemail(string email) {

    return 0;
}

int checkvalidpass(string pass) {
    return 0;
}

int checkvalidname(string name) {
    return 0;
}




int main(void)
{
    int c, id=-1;
    string name, email, passw;
    bool exit = 0, val;
    while (exit == 0) {
        
        std::cout << "1. Log in\n2. Register\n3. Forgotten password\n0. Exit" << endl;
        cin >> c;
        switch (c) {
        case 1:

            id=login();
            if (id == -1) {
                cout << "Wrong credentials!" << endl;
            }
            else {
                cout << "Successful login!" << endl;
            }
            break;
        case 2:
            std::cout << "Enter an email:" << endl;
            cin >> email;
            std::cout << "Enter a name:" << endl;
            cin >> name;
            std::cout << "Enter an password:" << endl;
            cin >> passw;
            val = checkvalidemail(email);
            reg(name, passw, email);
            break;
        case 3:
            pass();
            break;
        case 0:
            exit = true;
            break;
        }

        return 0;
    }
}