# SecureBank
A secure digital banking portal to demonstrate defensive programming techniques, user authentication, and mitigation of SQL Injection vulnerabilities.

# Project Overview
SecureBank is a functional web application developed to provide a secure environment for managing simulated financial transactions. This project serves as a practical demonstration of integrating modern web technologies with robust security protocols to protect sensitive user data from common cyber threats.

# Core Features
User Authentication: Secure registration and login system utilizing cryptographic password hashing.

Dashboard Management: Real time balance tracking in Saudi Riyals for every registered account holder.

Funds Transfer: A module allowing users to send money to other bank members while maintaining data integrity.

Role-Based Access: Specialized administrative interface to manage and monitor all system accounts.

# Security Implementation
The primary focus of this project is the prevention of SQL Injection (SQLi). By transitioning from direct query concatenation to PHP Data Objects (PDO) Prepared Statements, the application effectively neutralizes injection attempts and ensures that user input is never executed as database commands.

# Developers
- Turki Ali Ammari
- Hussam Abdullah Almajnuni
- Yazan Saad Mousa
- Almuthanna Yahia Alyousef
- Ahmed Yousef Alfayez
