---
layout: home
title: Page Components
nav_order: 2
parent: Frontend
grandparent: Developers
---

# 📄 Page Component Overview

Page components are the top-level views in the frontend, directly mapped to specific routes. Each page typically contains layout, forms, and interaction logic specific to that route, while delegating UI elements to reusable components.

---

## 🎯 Purpose

- Encapsulate **route-level logic** (e.g., login, registration, dashboard)
- Utilize **hooks**, **context**, and **forms** to manage state and side effects
- Maintain seperation of concerns by pushing reusable UI into components

---

## 🔍 Anatomy of a Page Component

Here's an overview using the `Login` page (`src/features/auth/pages/Login.tsx`) as an example:

```tsx
const Login: React.FC = () => {
  const { register, handleSubmit, formState: { errors } } = useForm<LoginFormValues>({
    resolver: yupResolver(loginSchema)
  });

  const { login, loading, error } = useLoginUser();
  const { refresh } = useAuth();

  const onSubmit = async (data: LoginFormValues) => {
    try {
      await login(data);
      await refresh();
    } catch (err) {
      console.log(err);
    }
  };

  return (
    <AuthLayout>
      {error && <p className="text-red-500">{error}</p>}
      <h1 className="text-2xl font-bold text-center">Welcome back!</h1>
      <form onSubmit={handleSubmit(onSubmit)} className="mt-6 flex flex-col gap-4">
        <Input
          {...register('email')}
          startAdornment={<IoIosMail className="text-[#FA3C25]" />}
          placeholder="Enter your email"
          error={errors.email?.message}
        />
        <Input
          {...register('password')}
          startAdornment={<IoIosLock className="text-[#FA3C25]" />}
          placeholder="Enter your password"
          type="password"
          error={errors.password?.message}
        />
        <Button loading={loading} type="submit">Sign in</Button>
        <p className="text-center">Don’t have an account? <Link to="/register" className="text-[#FA3C25]">Sign up</Link></p>
      </form>
    </AuthLayout>
  );
};
```

**🧠 Key Concepts About the Login Component:**

- UI components are separated out for reusability
- Custom hooks are used to manage state and side effects
- Form validation and submission are handled using **React Hook Form** and **Yup**
- Component only renders the view, leaving logic to hooks and services

---

## 🧪 Testing

Each page component should have unit tests or integration tests for:

- Conditional rendering such as `loading` and `error` states

---
